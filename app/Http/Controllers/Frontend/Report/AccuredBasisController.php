<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use App\Models\Period;
use App\Models\Payable;
use Illuminate\Http\Request;
use App\Actions\Reports\GstBas;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class AccuredBasisController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->findOrFail(client()->id);
        if ($client->gst_method == 2) {
            return view('frontend.report.accrued_basis.select_activity', compact('client'));
        } else {
            Alert::error('Check your GST method', 'You must check GST method because you are in Cash');
            return back();
        }
    }
    public function report(Request $request, GstBas $gst)
    {
        $period = Period::where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)->first();
        if ($period) {
            $data = $gst->acured($request);
            return view('frontend.report.accrued_basis.report', $data);
        } else {
            Alert::error('Period Data Not Found!', 'You must create a period before make report!');
            return redirect()->back();
        }
    }

    public function payable(Request $r)
    {
        try {
            $payable =  Payable::where('client_id', $r->client_id)->where('profession_id', $r->profession_id)->first();
            if ($payable != '') {
                $payable->update(['payable' => $r->payable, 'client_id' => $r->client_id, 'profession_id' => $r->profession_id]);
            } else {
                Payable::create(['payable' => $r->payable, 'client_id' => $r->client_id, 'profession_id' => $r->profession_id]);
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
