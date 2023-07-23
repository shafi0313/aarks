<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use App\Models\Period;
use Illuminate\Http\Request;
use App\Actions\Reports\GstBas;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class CashBasisController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->findOrFail(client()->id);
        if ($client->gst_method == 1) {
            return view('frontend.report.cash_basis.select_activity', compact('client'));
        } else {
            Alert::error('Check your GST method', 'You must check GST method because you are in Accrued');
            return back();
        }
    }
    public function report(Request $request, GstBas $gst)
    {
        // return $request;
        $period = Period::where('client_id', $request->client_id)
            ->where('profession_id', $request->profession_id)->first();
        if ($period) {
            // return
            $data = $gst->cash($request);
            return view('frontend.report.cash_basis.report', $data);
        } else {
            Alert::error('Period Data Not Found!', 'You must create a period before make report!');
            return redirect()->back();
        }
    }
}
