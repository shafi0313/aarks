<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use App\Models\Period;
use Illuminate\Http\Request;
use App\Actions\Reports\GstBas;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class GstAccruedBasisController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.accrued_basis.index')) {
            return $error;
        }

        $clients = getClientsWithPayment();
        return view('admin.reports.accrued_basis.index', compact('clients'));
    }
    public function date(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.accrued_basis.index')) {
            return $error;
        }
        $period = Period::where('client_id', $client->id)->first();
        if ($client->gst_method != 2) {
            Alert::error('Check your GST method', 'You must check GST method because you are in Cash');
            return back();
        }
        if ($period) {
            return view('admin.reports.accrued_basis.date', compact(['client']));
        } else {
            Alert::error('Period Data Not Found!', 'You must create a period before make report!');
            return redirect()->back();
        }
    }
    public function report(Request $request, GstBas $gst)
    {
        if ($error = $this->sendPermissionError('admin.accrued_basis.index')) {
            return $error;
        }
        $data = $gst->consoleAcured($request);
        return view('admin.reports.accrued_basis.report', $data);
    }
}
