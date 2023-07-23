<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use App\Models\Period;
use Illuminate\Http\Request;
use App\Actions\Reports\GstBas;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class GstAccruedBasisController extends Controller
{
    public function index(Client $client)
    {
        $client = Client::findOrFail(client()->id);
        if ($client->gst_method != 2) {
            Alert::error('Check your GST method', 'You must check GST method because you are in Cash');
            return back();
        }
        $period = Period::where('client_id', $client->id)->first();
        if ($period) {
            return view('frontend.report.gst-bas.accrued.console.index', compact(['client']));
        } else {
            Alert::error('Period Data Not Found!', 'You must create a period before make report!');
            return redirect()->back();
        }
    }
    public function report(Request $request, GstBas $gst)
    {
        $data = $gst->consoleAcured($request);
        return view('frontend.report.gst-bas.accrued.console.report', $data);
    }
}
