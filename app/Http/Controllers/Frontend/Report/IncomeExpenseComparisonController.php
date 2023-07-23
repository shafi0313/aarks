<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Profession;
use App\Models\BudgetEntry;
use Illuminate\Http\Request;
use App\Actions\Reports\GstPeriodic;
use App\Http\Controllers\Controller;
use App\Actions\Reports\TrialBalance;

class IncomeExpenseComparisonController extends Controller
{
    public function index(Request $request)
    {
        return view('frontend.report.income-expense-comparison.index');
    }
    public function report(Request $request, GstPeriodic $periodic)
    {
        $request->validate([
            'client_id' => 'required',
            'profession_id' => 'required',
            'year' => 'required',
        ]);
        $client     = Client::find($request->client_id);
        $profession = Profession::find($request->profession_id);
        $periods    = Period::whereClientId($client->id)->whereProfessionId($profession->id)->where('year', $request->year)->get();
        // return
        $accrueds =  Gsttbl::with(['accountCodes'=> fn ($q) => $q->where('client_id', $request->client_id)->select(['id','name','code'])])
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->whereIn('period_id', $periods->pluck('id')->toArray())
            ->where('source', '!=', 'INV')
            ->orderBy('chart_code')->get()->groupBy('period_id');
        if ($client->gst_method == 1) {
            return view('frontend.report.income-expense-comparison.cash_report', compact(['client', 'profession', 'periods', 'accrueds']));
        } else {
            return view('frontend.report.income-expense-comparison.accured_report', compact(['client', 'profession', 'periods', 'accrueds']));
        }
    }
}
