<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Actions\Reports\ProfitLossReport;

class AccumulatedPlGstReport extends Controller
{
    // Accumulated P/L GST Exclusive
    public function accumExclIndex()
    {
        $client = client::findOrFail(client()->id);
        return view('frontend.report.pl.accum.excl.select_activity', compact(['client']));
    }
    public function accumExcleRport(Request $request, ProfitLossReport $report)
    {
        return $report->consoleExcl($request, 'frontend.report.pl.accum.excl.report');
    }

    // Accumulated P/L GST Inclusive
    public function accumInclIndex()
    {
        $client = client::findOrFail(client()->id);
        return view('frontend.report.pl.accum.incl.select_activity', compact(['client']));
    }
    public function accumIncleRport(Request $request, ProfitLossReport $report)
    {
        return $report->consoleIncl($request, 'frontend.report.pl.accum.incl.report');
    }
}
