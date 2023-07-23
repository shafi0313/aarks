<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\Reports\ComperativeFinancial;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ComperativeFinancialReportController extends Controller
{
    public function index()
    {
        $client = Client::find(client()->id);
        return view('frontend.report.comperative_financial_report.financial_report', compact('client'));
    }
    public function report(Request $request, ComperativeFinancial $financial)
    {
        $client = Client::find(client()->id);
        $data   = $financial->report($request, $client);

        if ($request->has('print') && $request->print == true) {
            $pdf = PDF::loadView('admin.reports.comperative_financial_report.print', $data);
            return $pdf->stream('Comperative Financial Report.pdf');
        }
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'report' => 'Comperative Financial Report'])
            ->log('Report > Comperative Financial Report > ' . $client->fullname);

        return view('frontend.report.comperative_financial_report.final_report', $data);
    }
}
