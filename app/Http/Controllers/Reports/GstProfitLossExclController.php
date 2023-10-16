<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Reports\ProfitLossReport;

class GstProfitLossExclController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.profit_loss_excl.index')) {
            return $error;
        }

        $clients = getClientsWithPayment();
        return view('admin.reports.profit_loss.excl.index', compact('clients'));
    }
    public function profession($id)
    {
        if ($error = $this->sendPermissionError('admin.profit_loss_excl.index')) {
            return $error;
        }
        $client = Client::with('professions')->findOrFail($id);
        return view('admin.reports.profit_loss.excl.select_profession', compact('client'));
    }
    public function date(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.profit_loss_excl.index')) {
            return $error;
        }
        return view('admin.reports.profit_loss.excl.report_period', compact(['client', 'profession']));
    }
    public function report(Request $request, ProfitLossReport $report)
    {
        if ($error = $this->sendPermissionError('admin.profit_loss_excl.index')) {
            return $error;
        }

        return $report->excl($request, 'admin.reports.profit_loss.excl.final_report');
        // return view('admin.reports.profit_loss.excl.final_report', compact(['client', 'profession', 'from', 'to', 'data']));
    }
}
