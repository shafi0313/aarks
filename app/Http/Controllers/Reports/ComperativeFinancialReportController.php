<?php

namespace App\Http\Controllers\Reports;

use Carbon\Carbon;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use \PDF;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\Reports\ComperativeFinancial;

class ComperativeFinancialReportController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.comperative_financial_report.index')) {
            return $error;
        }

        $clients = client::all();
        return view('admin.reports.comperative_financial_report.index', compact('clients'));
    }
    public function selectReport(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.comperative_financial_report.index')) {
            return $error;
        }
        return view('admin.reports.comperative_financial_report.financial_report', compact('client'));
    }
    public function report(Client $client, Request $request, ComperativeFinancial $financial)
    {
        if ($error = $this->sendPermissionError('admin.comperative_financial_report.index')) {
            return $error;
        }
        $data = $financial->report($request, $client);
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'report' => 'Comperative Financial Report'])
            ->log('Report > Comperative Financial Report > ' . $client->fullname);

        return view('admin.reports.comperative_financial_report.final_report', $data);
    }

    public function print(Client $client, Request $request, ComperativeFinancial $financial)
    {
        $data = $financial->report($request, $client);

        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'report' => 'Console Financial Report'])
            ->log('Report > Console Financial Report > ' . $client->fullname);

        // return view('admin.reports.comperative_financial_report.print', $data);
        $pdf = PDF::loadView('admin.reports.comperative_financial_report.print', $data);
        return $pdf->stream();
    }
}
