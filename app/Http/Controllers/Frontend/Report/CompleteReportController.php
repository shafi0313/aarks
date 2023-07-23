<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\Reports\CompleteFinancial;
use App\Http\Controllers\Reports\CompleteFinancialReportController;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


class CompleteReportController extends Controller
{
    public function index(Request $request)
    {
        $client = Client::with('professions')->where('id', client()->id)->first();
        return view('frontend.report.complete-balance-report.profession', compact('client'));
    }
    public function selectReport(Request $request)
    {
        $client = Client::find(client()->id);
        $request = $request;
        return view('frontend.report.complete-balance-report.select-report', compact('client', 'request'));
    }
    public function report(Request $request, Profession $profession, CompleteFinancial $complete)
    {
        $client = Client::find(client()->id);
        $data = $complete->report($request, $client, $profession);
        if ($request->has('print') && $request->print == true) {
            $pdf = PDF::loadView('admin.reports.complete_financial_report.print', $data);
            return $pdf->stream('Complete Financial Report.pdf');
        }
        return view('frontend.report.complete-balance-report.report', $data);
    }
}
