<?php

namespace App\Http\Controllers\Reports;

use \PDF;
use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Http\Controllers\Controller;
use App\Actions\Reports\CompleteFinancial;
use App\Actions\Reports\CompleteFinancialTFAction;

class CompleteFinancialReportTFController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.complete_financial_report_t_f.index')) {
            return $error;
        }

        $clients = getClientsWithPayment();
        return view('admin.reports.complete_financial_report_t_f.index', compact('clients'));
    }
    public function profession($id)
    {
        if ($error = $this->sendPermissionError('admin.complete_financial_report_t_f.index')) {
            return $error;
        }
        $client = Client::with('professions')->findOrFail($id);
        return view('admin.reports.complete_financial_report_t_f.select_profession', compact('client'));
    }
    public function selectReport(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.complete_financial_report_t_f.index')) {
            return $error;
        }
        return view('admin.reports.complete_financial_report_t_f.financial_report', compact('client', 'profession'));
    }
    public function report(Client $client, Profession $profession, Request $request, CompleteFinancialTFAction $complete)
    {
        if ($error = $this->sendPermissionError('admin.complete_financial_report_t_f.index')) {
            return $error;
        }
        // return
        $data = $complete->report($request, $client, $profession);
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Complete Financial Report'])
            ->log('Report > Complete Financial Report > ' . $client->fullname . ' > ' . $profession->name);

        return view('admin.reports.complete_financial_report_t_f.final_report', $data);
    }

    public function print(Client $client, Profession $profession, Request $request, CompleteFinancial $complete)
    {
        $data = $complete->report($request, $client, $profession);

        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'report' => 'Console Financial Report'])
            ->log('Report > Console Financial Report > ' . $client->fullname);

        // return view('admin.reports.complete_financial_report_t_f.print', $data);
        $pdf = PDF::loadView('admin.reports.complete_financial_report_t_f.print', $data);
        return $pdf->stream('Complete Financial Report.pdf');
    }
}
