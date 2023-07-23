<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Actions\Reports\ProfitLossReport;

class ExclController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->findOrFail(client()->id);
        if ($client->is_gst_enabled) {
            return view('frontend.report.pl.excl.select_activity', compact('client'));
        } else {
            Alert::error('Check your GST', 'You must check GST because your not enabled');
            return back();
        }
    }
    public function report(Request $request, ProfitLossReport $report)
    {
        // return $request;
        return $report->excl($request, 'frontend.report.pl.excl.report');
    }
}
