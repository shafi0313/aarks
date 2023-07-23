<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class InclController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->findOrFail(client()->id);
        if ($client->is_gst_enabled) {
            return view('frontend.report.pl.incl.select_activity', compact('client'));
        } else {
            Alert::error('Check your GST', 'You must check GST because your not enabled');
            return back();
        }
    }
    public function report(Request $request)
    {
        // return $request;
        if ($request->from_date != '' and $request->to_date != '') {
            $from_date     = makeBackendCompatibleDate($request->from_date)->format('d/m/Y');
            $to_date       = makeBackendCompatibleDate($request->to_date)->format('d/m/Y');
            $start_date    = makeBackendCompatibleDate($request->from_date)->format('Y-m-d');
            $end_date      = makeBackendCompatibleDate($request->to_date)->format('Y-m-d');
            $client_id     = $request->client_id;
            $profession    = Profession::findOrFail($request->profession_id);
            $profession_id = $profession->id;
            $client        = Client::findOrFail($client_id);

            $income   = GeneralLedger::where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->where('chart_id', 'like', '1%')
                ->get();
            $totalIncome = $income->where('balance_type', 1)->sum('balance') - $income->where('balance_type', 2)->sum('balance');

            $expense  = GeneralLedger::where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->where('chart_id', 'like', '2%')->get();
            $totalExpense = $expense->where('balance_type', 1)->sum('balance') - $expense->where('balance_type', 2)->sum('balance');

            $incomeCodes = GeneralLedger::with('client_account_code')
                ->select('*', DB::raw('sum(credit) as inCredit'))
                ->where('chart_id', 'like', '1%')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
            $expensCodes = GeneralLedger::with('client_account_code')
                ->select('*', DB::raw('sum(debit) as inDebit'))
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
                return view('frontend.report.pl.incl.report', compact(['to_date', 'from_date', 'client', 'totalIncome', 'totalExpense', 'incomeCodes', 'expensCodes']));
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
