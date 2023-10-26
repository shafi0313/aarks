<?php
namespace App\Actions\DataStore;

use Exception;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Profession;
use App\Models\BudgetEntry;
use App\Models\BusinessPlan;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BusinessPlanAction extends Controller
{
    public function budgetEntry(Request $request, $client, $profession, $path = null)
    {
        // $date     = makeBackendCompatibleDate($request->date)->subYear();
        $date = Carbon::parse('30-Jun-'. $request->year);
        $end_date = $date->format('Y-m-d');
        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $start_date = $date->format('Y') . '-07-01';
        } else {
            $start_date = $date->format('Y') - 1 . '-07-01';
        }
        // return $start_date;
        $start     = $date->format('dm');
        $last_year = $date->format('Y') + 1 . '-06-30';
        $client_id = $client->id;
        if ($start == '0107') {
            $ledgers = GeneralLedger::with('client_account_code')->select('*', DB::raw("sum(balance) as trail_balance"))
                ->where('client_id', $client_id)
                ->where('profession_id', $profession->id)
                ->where('date', '<=', $end_date)
                ->whereNotIn('chart_id', [999998, 999999])
                ->where('chart_id', 'not like', '1%')
                ->where('chart_id', 'not like', '2%')
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get()
                ->groupBy(fn ($item) => substr($item->chart_id, -6, 1));
        } else {
            $ledgers = GeneralLedger::with('client_account_code')->select('*', DB::raw("sum(balance) as trail_balance"))
                ->where('client_id', $client_id)
                ->where('profession_id', $profession->id)
                ->where(function ($q) use ($start_date, $end_date) {
                    $q->where(function ($oneTwo) use ($start_date, $end_date) {
                        $oneTwo->where('date', '>=', $start_date)
                            ->where('date', '<=', $end_date)
                            ->where(function ($q) {
                                $q->where('chart_id', 'like', '1%')
                                    ->orWhere('chart_id', 'like', '2%');
                            });
                    })
                    ->orWhere(function ($fiveNine) use ($end_date) {
                        $fiveNine->where('date', '<=', $end_date)
                            ->where(function ($q) {
                                $q->where('chart_id', 'like', '5%')
                                    ->orWhere('chart_id', 'like', '9%');
                            });
                    });
                })
                ->whereNotIn('chart_id', [999998, 999999])
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get()
                ->groupBy(fn ($item) => substr($item->chart_id, -6, 1));
        }
        $CRetains =  pl($client, $profession, $date);

        // $codes = ClientAccountCode::where('client_id', $client->id)
        // ->where('profession_id', $profession->id)
        // ->orderBy('code')->get();
        return compact('ledgers', 'date', 'client', 'CRetains', 'profession');
    }

    public function budget(Request $request, $client, $profession, $path = null)
    {
        $date = Carbon::parse('30-Jun-'. $request->year)->subYear();
        $end_date = $date->format('Y-m-d');
        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $start_date = $date->format('Y') . '-07-01';
        } else {
            $start_date = $date->format('Y') - 1 . '-07-01';
        }
        // return $start_date;
        $start     = $date->format('dm');
        $last_year = $date->format('Y') + 1 . '-06-30';
        $client_id = $client->id;
        if ($start == '0107') {
            $ledgers = GeneralLedger::with('client_account_code')->select('*', DB::raw("sum(balance) as trail_balance"))
                ->where('client_id', $client_id)
                ->where('profession_id', $profession->id)
                ->where('date', '<=', $end_date)
                ->whereNotIn('chart_id', [999998, 999999])
                ->where('chart_id', 'not like', '1%')
                ->where('chart_id', 'not like', '2%')
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get()
                ->groupBy(fn ($item) => substr($item->chart_id, -6, 1));
        } else {
            $ledgers = GeneralLedger::with(['client_account_code'=> fn($q) => $q->selectRaw('id,code,name,category_id')])
                ->selectRaw('id,chart_id,client_account_code_id,balance_type, sum(balance) as trail_balance')
                ->where('client_id', $client_id)
                ->where('profession_id', $profession->id)
                ->where(function ($query) use ($start_date, $end_date) {
                    $query->whereBetween('date', [$start_date, $end_date])
                        ->whereIn(DB::raw("substring(chart_id, 1, 1)"), ['1', '2'])
                        ->orWhere(function ($query) use ($end_date) {
                            $query->where('date', '<=', $end_date)
                                ->whereIn(DB::raw("substring(chart_id, 1, 1)"), ['5', '9']);
                        });
                })
                ->whereNotIn('chart_id', [999998, 999999])
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get()
                ->groupBy(fn ($item) => substr($item->chart_id, -6, 1));
        }
        $CRetains =  pl($client, $profession, $date);
        return compact('ledgers', 'date', 'client', 'CRetains', 'profession');
    }

    public function store(Request $request)
    {
        $client     = Client::findOrFail($request->client_id);
        $profession = Profession::findOrFail($request->profession_id);
        $date       = Carbon::parse($request->date)->format('Y-m-d');
        $entries    = $request->entries;
        $tran_id    = transaction_id('BPE');
        // dd($entries);

        $plans = [];
        foreach ($request->entries as $key => $entry) {
            $data['client_id']       = (int) $request->client_id;
            $data['profession_id']   = (int) $request->profession_id;
            $data['chart_id']        = (int) $entry['chart_id'];
            $data['date']            = $date;
            $data['tran_id']         = $tran_id;
            $data['source']          = 'BPE';
            $data['narration']       = 'Business Plan for ' . $client->fullname . ' for ' . $profession->name . ' at ' . $date;
            $data['last_amount']     = (int) $entry['last_amount'];
            $data['last_percent']    = (int) $entry['last_percent'];
            $data['account_code_id'] = (int) $entry['account_code_id'];

            $months = [];
            foreach ($request->months as $month) {
                $months[$month] = json_encode($entry[$month]);
            }
            // return json_encode($months);
            $data['months'] = json_encode($months);
            $data['created_at'] = now();
            $data['updated_at'] = now();
            $plans[] = $data;
        }
        try {
            BusinessPlan::insert($plans);
        } catch (\Exception $e) {
            // throw new Exception($e->getMessage());
            throw new Exception('Something went wrong');
            // throw $e->getMessage();
        }
    }
    public function update(Request $request, $plan)
    {

        $client     = Client::findOrFail($request->client_id);
        $profession = Profession::findOrFail($request->profession_id);
        $date       = Carbon::parse($request->date)->format('Y-m-d');
        $entries    = $request->entries;
        $tran_id    = $plan->tran_id;
        // dd($entries);

        $plans = [];
        foreach ($request->entries as $key => $entry) {
            $data['client_id']       = (int) $request->client_id;
            $data['profession_id']   = (int) $request->profession_id;
            $data['chart_id']        = (int) $entry['chart_id'];
            $data['date']            = $date;
            $data['tran_id']         = $tran_id;
            $data['source']          = 'BPE';
            $data['narration']       = 'Business Plan for ' . $client->fullname . ' for ' . $profession->name . ' at ' . $date;
            $data['last_amount']     = (int) $entry['last_amount'];
            $data['last_percent']    = (int) $entry['last_percent'];
            $data['account_code_id'] = (int) $entry['account_code_id'];

            $months = [];
            foreach ($request->months as $month) {
                $months[$month] = json_encode($entry[$month]);
            }
            // return json_encode($months);
            $data['months'] = json_encode($months);
            $data['created_at'] = now();
            $data['updated_at'] = now();
            $plans[] = $data;
        }
        DB::beginTransaction();
        try {
            BusinessPlan::whereClientId($client->id)->whereProfessionId($profession->id)->whereTranId($plan->tran_id)->where('date', $date)->delete();
            BusinessPlan::insert($plans);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // throw new Exception('Something went wrong');
            throw new Exception($e->getMessage());
        }
    }
    public function details(Request $request, $client, $profession)
    {
        $date = Carbon::parse('30-Jun-'. $request->year);
        $start_date = $date->format('Y') - 1 . '-07-01';
        $end_date = $date->format('Y-m-d');
        // if ($date->format('m') >= 07 & $date->format('m') <= 12) {
        //     $start_date = $date->format('Y') . '-07-01';
        // } else {
        //     $start_date = $date->format('Y') - 1 . '-07-01';
        // }


        // return $start_date;
        $start     = $date->format('dm');
        $last_year = $date->format('Y') + 1 . '-06-30';
        $client_id = $client->id;
        $ledgers = GeneralLedger::with(['client_account_code' => fn($q) => $q->selectRaw('id, code, name, category_id')])
            ->selectRaw('id,date, chart_id, client_account_code_id, balance_type, sum(balance) as _balance, DATE_FORMAT(date, "%m") as month')
            ->where('client_id', $client_id)
            ->where('profession_id', $profession->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->whereNotIn('chart_id', [999998, 999999])
            ->groupBy('month', 'chart_id')
            ->orderBy('chart_id')
            ->get()
            ->groupBy([fn ($item) => substr($item->chart_id, -6, 1),'chart_id']);
        $payables = GeneralLedger::with(['client_account_code' => fn($q) => $q->selectRaw('id, code, name, category_id')])
            ->selectRaw('id,date, chart_id, client_account_code_id, balance_type, sum(balance) as _balance, DATE_FORMAT(date, "%m") as month')
            ->where('client_id', $client_id)
            ->where('profession_id', $profession->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->where('chart_id', 912100)
            ->groupBy('month')
            ->get();
        $clearings = GeneralLedger::with(['client_account_code' => fn($q) => $q->selectRaw('id, code, name, category_id')])
            ->selectRaw('id,date, chart_id, client_account_code_id, balance_type, sum(balance) as _balance, DATE_FORMAT(date, "%m") as month')
            ->where('client_id', $client_id)
            ->where('profession_id', $profession->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->where('chart_id', 912101)
            ->groupBy('month')
            ->get();
        return compact('ledgers', 'date', 'client', 'profession', 'payables','clearings');
    }
}
