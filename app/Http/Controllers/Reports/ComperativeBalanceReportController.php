<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use \PDF;
use App\Http\Controllers\Controller;
use App\Actions\Reports\ComperativeBalance;

class ComperativeBalanceReportController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.comperative_bs.index')) {
            return $error;
        }

        $clients = getClientsWithPayment();
        return view('admin.reports.comperative_balance_sheet.index', compact('clients'));
    }
    public function profession($id)
    {
        if ($error = $this->sendPermissionError('admin.comperative_bs.index')) {
            return $error;
        }
        $client = Client::with('professions')->findOrFail($id);
        return view('admin.reports.comperative_balance_sheet.select_profession', compact('client'));
    }
    public function selectDate(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.comperative_bs.index')) {
            return $error;
        }
        return view('admin.reports.comperative_balance_sheet.select_date', compact(['client', 'profession']));
    }
    public function report(Request $request, Client $client, Profession $profession, ComperativeBalance $balance)
    {
        if ($error = $this->sendPermissionError('admin.comperative_bs.index')) {
            return $error;
        }
        $data = $balance->report($request, $client, $profession);
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Comparative Balance Sheet Report'])
            ->log('Report > Comperative Balance Sheet Report > ' . $client->fullname . ' > ' . $profession->name);
        return view('admin.reports.comperative_balance_sheet.report', $data);
    }
    public function print(Request $request, Client $client, Profession $profession, ComperativeBalance $balance)
    {
        if ($error = $this->sendPermissionError('admin.comperative_bs.index')) {
            return $error;
        }
        $data = $balance->report($request, $client, $profession);

        $pdf = PDF::loadView('admin.reports.comperative_balance_sheet.print', $data);
        return $pdf->stream('Complete Financial Report.pdf');
    }
}
