<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Reports\ProfitLossReport;

class GstProfitLossInclController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.profit_loss_incl.index')) {
            return $error;
        }

        $clients = getClientsWithPayment();
        return view('admin.reports.profit_loss.incl.index', compact('clients'));
    }
    public function profession($id)
    {
        if ($error = $this->sendPermissionError('admin.profit_loss_incl.index')) {
            return $error;
        }
        $client = Client::with('professions')->findOrFail($id);
        return view('admin.reports.profit_loss.incl.select_profession', compact('client'));
    }
    public function date($clientId, $proId)
    {
        if ($error = $this->sendPermissionError('admin.profit_loss_incl.index')) {
            return $error;
        }
        $client = Client::findOrFail($clientId);
        $profession = Profession::findOrFail($proId);
        return view('admin.reports.profit_loss.incl.report_period', compact(['client', 'profession']));
    }
    public function report(Request $request, ProfitLossReport $report)
    {
        // return $request;
        if ($error = $this->sendPermissionError('admin.profit_loss_incl.index')) {
            return $error;
        }
        return $report->incl($request, 'admin.reports.profit_loss.incl.final_report');
    }
}
