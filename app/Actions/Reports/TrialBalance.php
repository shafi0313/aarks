<?php

namespace App\Actions\Reports;

use App\Models\Client;
use App\Models\Profession;
use App\Models\BudgetEntry;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TrialBalance extends Controller
{
    public function report(Request $request, $client, $profession, $path = null)
    {
        $date     = makeBackendCompatibleDate($request->date);
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
            $ledgers = GeneralLedger::select('*', DB::raw("sum(balance) as trail_balance"))
                ->where('client_id', $client_id)
                ->where('profession_id', $profession->id)
                ->where('date', '<=', $end_date)
                ->whereNotIn('chart_id', [999998, 999999])
                ->where('chart_id', 'not like', '1%')
                ->where('chart_id', 'not like', '2%')
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
        } else {
            $ledgers = GeneralLedger::select('*', DB::raw("sum(balance) as trail_balance"))
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
                ->get();
        }

        $CRetains =  pl($client, $profession, $date);
        $retains =  retain($client, $profession, $date);

        $codes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('code')->get();
        return compact('codes', 'ledgers', 'date', 'client', 'retains', 'CRetains', 'profession');
    }

    public function consoleReport(Request $request, $client, $path = null)
    {
        $date     = makeBackendCompatibleDate($request->date);
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
        $professions = $client->professions->pluck('id')->toArray();
        if ($start == '0107') {
            $ledgers = GeneralLedger::select('*', DB::raw("sum(balance) as trail_balance"))
                ->where('client_id', $client_id)
                ->whereIn('profession_id', $professions)
                ->where('date', '<=', $end_date)
                ->whereNotIn('chart_id', [999998, 999999])
                ->where('chart_id', 'not like', '1%')
                ->where('chart_id', 'not like', '2%')
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
        } else {
            $ledgers = GeneralLedger::select('*', DB::raw("sum(balance) as trail_balance"))
                ->where('client_id', $client_id)
                ->whereIn('profession_id', $professions)
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
                ->get();
        }
        // $retains = GeneralLedger::select('*', DB::raw("sum(balance) as totalRetain"))
        //     ->where('client_id', $client_id)
        //     ->where('date', '<', $start_date)
        //     ->where('chart_id', 999999)
        //     ->groupBy('chart_id')
        //     ->first();
        $retains =  consoleRetain($client, $date);
        $CRetains =  accum_pl($client, $date);

        $codes = ClientAccountCode::where('client_id', $client->id)
            ->whereIn('profession_id', $professions)
            ->orderBy('code')->get();
        return compact('codes', 'ledgers', 'date', 'client', 'retains', 'CRetains');
    }

    public function budgetReport(Request $request, $path = null)
    {
        $client     = Client::findOrFail($request->client_id);
        $profession = Profession::findOrFail($request->profession_id);
        $date       = makeBackendCompatibleDate($request->date)->addYear();
        $current    = makeBackendCompatibleDate($request->date);
        $end_date   = $date->format('Y-m-d');
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
            $ledgers = GeneralLedger::select('id', 'chart_id', 'balance_type', DB::raw("sum(balance) as actual_amount"))
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
            $ledgers = GeneralLedger::select('id', 'chart_id', 'balance_type', DB::raw("sum(balance) as actual_amount"))
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

        $currentBudgets = BudgetEntry::with(['chart' => fn ($q) => $q->where('client_id', $client->id)])->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            // ->whereIn('date', $dates)
            ->where('date', $current->format('Y-m-d'))
            ->get()
            ->groupBy(fn ($item) => substr($item->chart_id, -6, 1));
        return compact('ledgers', 'date', 'current', 'client', 'CRetains', 'profession', 'currentBudgets');
    }
}
