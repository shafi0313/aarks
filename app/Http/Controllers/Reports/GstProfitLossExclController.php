<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Actions\Reports\ProfitLossReport;

class GstProfitLossExclController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.profit_loss_excl.index')) {
            return $error;
        }

        $clients = Client::leftJoin('client_payment_lists', 'clients.id', '=', 'client_payment_lists.client_id')
            ->select('clients.id','clients.company', 'clients.first_name','clients.last_name','clients.email','clients.phone',
                    'client_payment_lists.status', 'client_payment_lists.is_expire', 'client_payment_lists.status')
            ->orderBy('client_payment_lists.status', 'desc')
            ->orderBy('client_payment_lists.is_expire', 'desc')
            ->get();
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
    }
}
