<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Actions\Reports\ProfitLossReport;

class ConsolePLController extends Controller
{
    // Accumulated P/L GST Exclusive
    public function exclindex()
    {
        if ($error = $this->sendPermissionError('admin.console_accum_excl.index')) {
            return $error;
        }
        $clients = getClientsWithPayment();
        return view('admin.reports.profit_loss.accum.excl.index', compact('clients'));
    }
    public function exclPeriod(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.console_accum_excl.index')) {
            return $error;
        }
        return view('admin.reports.profit_loss.accum.excl.report_period', compact(['client']));
    }
    public function excleRport(Request $request, ProfitLossReport $report)
    {
        // return $request;
        if ($error = $this->sendPermissionError('admin.console_accum_excl.index')) {
            return $error;
        }
        return $report->consoleExcl($request, 'admin.reports.profit_loss.accum.excl.final_report');
    }

    // Accumulated P/L GST Inclusive
    public function inclindex()
    {
        if ($error = $this->sendPermissionError('admin.console_accum_incl.index')) {
            return $error;
        }
        $clients = getClientsWithPayment();
        return view('admin.reports.profit_loss.accum.incl.index', compact('clients'));
    }
    public function inclPeriod(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.console_accum_incl.index')) {
            return $error;
        }
        return view('admin.reports.profit_loss.accum.incl.report_period', compact(['client']));
    }
    public function incleRport(Request $request, ProfitLossReport $report)
    {
        if ($error = $this->sendPermissionError('admin.console_accum_incl.index')) {
            return $error;
        }
        return $report->consoleIncl($request, 'admin.reports.profit_loss.accum.incl.final_report');
    }
}
