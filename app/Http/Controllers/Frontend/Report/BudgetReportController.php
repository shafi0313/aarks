<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use App\Models\Profession;
use App\Models\BudgetEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Reports\TrialBalance;
use RealRashid\SweetAlert\Facades\Alert;

class BudgetReportController extends Controller
{
    public function index(Request $request)
    {
        return view('frontend.report.budget.index');
    }
    public function report(Request $request, TrialBalance $trialBalance)
    {
        // return $request;
        if(!$request->filled('date')){
            Alert::error('Error', 'Please prepare the budget first!');
            return back();
        }
        // return
        $data = $trialBalance->budgetReport($request);
        return view('frontend.report.budget.report', $data);
    }
}
