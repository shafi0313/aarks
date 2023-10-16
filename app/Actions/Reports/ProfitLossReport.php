<?php

namespace App\Actions\Reports;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ProfitLossReport
{
    public function excl(Request $request, $path)
    {
        if ($request->from_date != '' && $request->to_date != '') {
            $s_date = makeBackendCompatibleDate($request->from_date);
            $e_date = makeBackendCompatibleDate($request->to_date);
            if ($e_date->format('m') >= 07 & $e_date->format('m') <= 12) {
                $start_year = $e_date->format('Y') . '-07-01';    //2020-07-01==2021-06-30
                $end_year   = $e_date->format('Y') + 1 . '-06-30';
            } else {
                $start_year = $e_date->format('Y') - 1 . '-07-01';  //2019-07-01==2020-06-30
                $end_year   = $e_date->format('Y') . '-06-30';
            }
            if ($s_date->format('Y-m-d') < $start_year && $s_date->format('Y-m-d') < $end_year) {
                Alert::warning('Please Check input Dates');
                return back();
            }

            $from_date     = $s_date->format('d/m/Y');
            $to_date       = $e_date->format('d/m/Y');

            $start_date    = $s_date->format('Y-m-d');
            $end_date      = $e_date->format('Y-m-d');
            $client_id     = $request->client_id;
            $profession    = Profession::find($request->profession_id);
            $profession_id = $profession->id;
            $client        = Client::find($client_id);
            // $totalIncome   = GeneralLedger::select('*', DB::raw("(sum(balance)) as totalIncome"))
            $income   = GeneralLedger::with([
                'client_account_code' => fn ($q) => $q->select(clientAccountCodeSetVisible())
            ])->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->where('chart_id', 'like', '1%')
                ->get(ledgerSetVisible());

            $totalIncome = $income->where('balance_type', 1)->sum('balance') - $income->where('balance_type', 2)->sum('balance');

            $expense  = GeneralLedger::with(['client_account_code' => fn ($q) => $q->select(clientAccountCodeSetVisible())])->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->where('chart_id', 'like', '2%')
                ->get(ledgerSetVisible());
            $totalExpense = $expense->where('balance_type', 1)->sum('balance') - $expense->where('balance_type', 2)->sum('balance');

            $incomeCodes = GeneralLedger::with(['client_account_code' => fn ($q) => $q->select(clientAccountCodeSetVisible())])
                ->select('*', DB::raw('sum(balance) as inBalance'))
                ->where('chart_id', 'like', '1%')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get(ledgerSetVisible());

            $expensCodes = GeneralLedger::with(['client_account_code' => fn ($q) => $q->select(clientAccountCodeSetVisible())])
                ->select('*', DB::raw('sum(balance) as exBalance'))
                ->Where('chart_id', 'like', '2%')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get(ledgerSetVisible());
            // if($expensCodes){
            //     return $expensCodes;
            // }
            $checkLedger = GeneralLedger::where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->get(ledgerSetVisible());
            if ($checkLedger->count()) {
                activity()
                    ->performedOn(new GeneralLedger())
                    ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Profit Loss GST Excl Report'])
                    ->log('Report > Profit Loss GST Excl Report > ' . $client->fullname . ' > ' . $profession->name);
                    
                return view($path, compact(['to_date', 'from_date', 'client', 'totalIncome', 'totalExpense', 'incomeCodes', 'expensCodes', 'profession']));
            } else {
                Alert::warning('There was no records matching with input dates!');
                return back();
            }
        } else {
            Alert::warning('Please Check input Dates');
            return back();
        }
    }
    public function incl(Request $request, $path)
    {
        if ($request->from_date != '' && $request->to_date != '') {
            $from_date     = makeBackendCompatibleDate($request->from_date)->format('d/m/Y');
            $to_date       = makeBackendCompatibleDate($request->to_date)->format('d/m/Y');
            $start_date    = makeBackendCompatibleDate($request->from_date)->format('Y-m-d');
            $end_date      = makeBackendCompatibleDate($request->to_date)->format('Y-m-d');
            $client_id     = $request->client_id;
            $profession    = Profession::findOrFail($request->profession_id);
            $profession_id = $profession->id;
            $client        = Client::find($client_id);

            $incomeCodes = GeneralLedger::with(['client_account_code' => fn ($q) => $q->select(clientAccountCodeSetVisible())])
                ->select('*', DB::raw('sum(credit) as inCredit'), DB::raw('sum(debit) as iDebit'))
                ->where('chart_id', 'like', '1%')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                // ->dd();
                ->get();
            $expensCodes = GeneralLedger::with(['client_account_code' => fn ($q) => $q->select(clientAccountCodeSetVisible())])
                ->select('*', DB::raw('sum(debit) as inDebit', DB::raw('sum(credit) as eCredit')))
                ->Where('chart_id', 'like', '2%')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
            $checkLedger = GeneralLedger::where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->get();

            if ($checkLedger->count()) {
                activity()
                    ->performedOn(new GeneralLedger())
                    ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Profit Loss GST Incle Report'])
                    ->log('Report > Profit Loss GST Incle Report > ' . $client->fullname . ' > ' . $profession->name);
                return view($path, compact(['to_date', 'from_date', 'client', 'incomeCodes', 'expensCodes', 'profession']));
            } else {
                Alert::warning('There was no data matching with input date!');
                return back();
            }
        } else {
            Alert::warning('Please Check input Dates');
            return back();
        }
    }
    public function consoleExcl(Request $request, $path)
    {
        if ($request->from_date != '' and $request->to_date != '') {
            $from_date  = makeBackendCompatibleDate($request->from_date)->format('d/m/Y');
            $to_date    = makeBackendCompatibleDate($request->to_date)->format('d/m/Y');
            $start_date = makeBackendCompatibleDate($request->from_date)->format('Y-m-d');
            $end_date   = makeBackendCompatibleDate($request->to_date)->format('Y-m-d');

            $client_id     = $request->client_id;
            $client        = Client::find($client_id);
            $professions = $client->professions->pluck('id')->toArray();

            // $income   = GeneralLedger::select('*', DB::raw("(sum(balance)) as totalIncome"))
            $income   = GeneralLedger::where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->whereIn('profession_id', $professions)
                ->where('chart_id', 'like', '1%')
                ->get();
            $totalIncome = $income->where('balance_type', 1)->sum('balance') - $income->where('balance_type', 2)->sum('balance');

            $expense = GeneralLedger::where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->whereIn('profession_id', $professions)
                ->where('chart_id', 'like', '2%')
                ->get();
            $totalExpense = $expense->where('balance_type', 1)->sum('balance') - $expense->where('balance_type', 2)->sum('balance');

            $incomeCodes = GeneralLedger::with(['client_account_code' => fn ($q) => $q->select(clientAccountCodeSetVisible())])
                ->select('*', DB::raw('sum(balance) as inBalance'))
                ->where('chart_id', 'like', '1%')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->whereIn('profession_id', $professions)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
            $expensCodes = GeneralLedger::with(['client_account_code' => fn ($q) => $q->select(clientAccountCodeSetVisible())])
                ->select('*', DB::raw('sum(balance) as exBalance'))
                ->Where('chart_id', 'like', '2%')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->whereIn('profession_id', $professions)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
            // if($expensCodes){
            //     return $expensCodes;
            // }
            $checkLedger = GeneralLedger::where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->whereIn('profession_id', $professions)
                ->get();
            if ($checkLedger->count()) {
                activity()
                    ->performedOn(new GeneralLedger())
                    ->withProperties(['client' => $client->fullname, 'report' => 'Console Accumulated Excl Report'])
                    ->log('Report > Console Accumulated Excl Report');
                return view($path, compact(['to_date', 'from_date', 'client', 'totalIncome', 'totalExpense', 'incomeCodes', 'expensCodes']));
            } else {
                Alert::warning('There was no records matching with input dates!');
                return back();
            }
        } else {
            Alert::warning('Please Check input Dates');
            return back();
        }
    }
    public function consoleIncl(Request $request, $path)
    {
        if ($request->from_date != '' && $request->to_date != '') {
            $from_date     = makeBackendCompatibleDate($request->from_date)->format('d/m/Y');
            $to_date       = makeBackendCompatibleDate($request->to_date)->format('d/m/Y');
            $start_date    = makeBackendCompatibleDate($request->from_date)->format('Y-m-d');
            $end_date      = makeBackendCompatibleDate($request->to_date)->format('Y-m-d');
            $client_id     = $request->client_id;
            $client        = Client::find($client_id);
            $professions = $client->professions->pluck('id')->toArray();

            $incomeCodes = GeneralLedger::with(['client_account_code' => fn ($q) => $q->select(clientAccountCodeSetVisible())])
                ->select('*', DB::raw('sum(credit) as inCredit'))
                ->where('chart_id', 'like', '1%')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->whereIn('profession_id', $professions)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                // ->dd();
                ->get();
            $expensCodes = GeneralLedger::with(['client_account_code' => fn ($q) => $q->select(clientAccountCodeSetVisible())])
                ->select('*', DB::raw('sum(debit) as inDebit'))
                ->Where('chart_id', 'like', '2%')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->whereIn('profession_id', $professions)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
            $checkLedger = GeneralLedger::where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->whereIn('profession_id', $professions)
                ->get();

            if ($checkLedger->count()) {
                activity()
                    ->performedOn(new GeneralLedger())
                    ->withProperties(['client' => $client->fullname, 'report' => 'Console Profit Loss GST Incle Report'])
                    ->log('Report > Profit Loss GST Incle Report > ' . $client->fullname);
                return view($path, compact(['to_date', 'from_date', 'client', 'incomeCodes', 'expensCodes']));
            } else {
                Alert::warning('There was no data matching with input date!');
                return back();
            }
        } else {
            Alert::warning('Please Check input Dates');
            return back();
        }
    }
}
