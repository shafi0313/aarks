<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use App\Models\Profession;
use App\Models\BudgetEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Reports\TrialBalance;
use RealRashid\SweetAlert\Facades\Alert;

class BudgetReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:admin.budget_report.index']);
    }
    public function index(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.budget_report.index')) {
            return $error;
        }
        return view('admin.reports.advance.budget.index');
    }
    public function report(Request $request, TrialBalance $trialBalance)
    {
        // return $request;
        if ($error = $this->sendPermissionError('admin.budget_report.index')) {
            return $error;
        }
        if (!$request->filled('date')) {
            Alert::error('Error', 'Please prepare the budget first!');
            return back();
        }
        // return
        $data = $trialBalance->budgetReport($request);
        return view('admin.reports.advance.budget.report', $data);
    }
}
