<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\Reports\BalanceSheet;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class BalanceSheetReportController extends Controller
{

    // balance_sheet
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.balance_sheet.index')) {
            return $error;
        }

        $clients = getClientsWithPayment();
        return view('admin.reports.balance_sheet.index', compact('clients'));
    }
    public function profession($id)
    {
        if ($error = $this->sendPermissionError('admin.balance_sheet.index')) {
            return $error;
        }
        $client = Client::with('professions')->findOrFail($id);
        return view('admin.reports.balance_sheet.select_profession', compact('client'));
    }
    public function date(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.balance_sheet.index')) {
            return $error;
        }
        return view('admin.reports.balance_sheet.select_date', compact(['client', 'profession']));
    }
    public function report(Request $request, Client $client, Profession $profession, BalanceSheet $balance)
    {
        if ($error = $this->sendPermissionError('admin.balance_sheet.index')) {
            return $error;
        }
        // return
        $data = $balance->report($request, $client, $profession);
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Balance Sheet Report'])
            ->log('Report > Balance Sheet Report > ' . $client->fullname . ' > ' . $profession->name);
        return view('admin.reports.balance_sheet.report', $data);
    }

    public function reportPDF(Request $request, Client $client, Profession $profession, BalanceSheet $balance)
    {
        if ($error = $this->sendPermissionError('admin.balance_sheet.index')) {
            return $error;
        }
        $data = $balance->report($request, $client, $profession);

        $pdf = PDF::loadView('admin.reports.balance_sheet.print', $data);
        return $pdf->stream('Complete Financial Report.pdf');
    }



    // Console Balance Sheet
    public function consoleIndex()
    {
        if ($error = $this->sendPermissionError('admin.console_balance_sheet.index')) {
            return $error;
        }

        $clients = getClientsWithPayment();
        return view('admin.reports.balance_sheet.console.index', compact('clients'));
    }
    public function consoleDate(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.console_balance_sheet.index')) {
            return $error;
        }
        return view('admin.reports.balance_sheet.console.select_date', compact(['client']));
    }
    public function consoleReport(Request $request, Client $client, BalanceSheet $balance)
    {
        if ($error = $this->sendPermissionError('admin.console_balance_sheet.index')) {
            return $error;
        }
        // return
        $data = $balance->consoleReport($request, $client);
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'report' => 'Console Balance Sheet Report'])
            ->log('Report > Console Balance Sheet Report ');
        return view('admin.reports.balance_sheet.console.report', $data);
    }
}
