<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\Reports\ConsoleFinancial;
use App\Http\Controllers\Reports\ConsoleFinancialReportController as ReportsConsoleFinancialReportController;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


class ConsoleFinancialReportController extends Controller
{
    public function index()
    {
        $client = Client::find(client()->id);
        return view('frontend.report.console_financial_report.financial_report', compact('client'));
    }
    public function report(Request $request, ConsoleFinancial $console)
    {
        $client = Client::find(client()->id);
        $data = $console->report($request, $client);

        if ($request->has('print') && $request->print == true) {
            $pdf = PDF::loadView('admin.reports.console_financial_report.print', $data);
            return $pdf->stream('Console Financial Report.pdf');
        }
        return view('frontend.report.console_financial_report.final_report', $data);
    }
}
