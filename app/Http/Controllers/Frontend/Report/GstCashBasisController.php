<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use App\Models\Period;
use Illuminate\Http\Request;
use App\ProfessionAccountCode;
use App\Actions\Reports\GstBas;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class GstCashBasisController extends Controller
{
    public function index()
    {
        $client = Client::findOrFail(client()->id);
        if ($client->gst_method != 1) {
            Alert::error('Check your GST method', 'You must check GST method because you are in Accrued');
            return back();
        }
        $period = Period::where('client_id', $client->id)->first();
        if (!$period) {
            Alert::error('Period Data Not Found!', 'You must create a period before make report!');
            return redirect()->back();
        }
        return view('frontend.report.gst-bas.cash.console.index', compact(['client']));
    }
    public function report(Request $request, GstBas $gst)
    {
        return $data = $gst->consoleCash($request, 'frontend.report.gst-bas.cash.console.report');
        // return view('frontend.report.gst-bas.cash.console.report', $data);
    }
}
