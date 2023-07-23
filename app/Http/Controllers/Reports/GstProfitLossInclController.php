<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Actions\Reports\ProfitLossReport;

class GstProfitLossInclController extends Controller
{
    // prfit and loss GST Inlcusic
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.profit_loss_incl.index')) {
            return $error;
        }

        $clients = Client::leftJoin('client_payment_lists', 'clients.id', '=', 'client_payment_lists.client_id')
            ->select('clients.id','clients.company', 'clients.first_name','clients.last_name','clients.email','clients.phone',
                    'client_payment_lists.status', 'client_payment_lists.is_expire', 'client_payment_lists.status')
            ->orderBy('client_payment_lists.status', 'desc')
            ->orderBy('client_payment_lists.is_expire', 'desc')
            ->get();
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
