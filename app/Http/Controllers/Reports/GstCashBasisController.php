<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use App\Models\Period;
use Illuminate\Http\Request;
use App\ProfessionAccountCode;
use App\Actions\Reports\GstBas;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class GstCashBasisController extends Controller
{

    // GST/BAS (Cash Basis)
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.cash_basis.index')) {
            return $error;
        }

        $clients = getClientsWithPayment();
        return view('admin.reports.cash_basis.index', compact('clients'));
    }
    public function profession($id)
    {
        if ($error = $this->sendPermissionError('admin.cash_basis.index')) {
            return $error;
        }
        $client = Client::with('professions')->findOrFail($id);
        if ($client->gst_method == 1) {
            return view('admin.reports.cash_basis.profession', compact('client'));
        } else {
            Alert::error('Check your GST method', 'You must check GST method because you are in Accrued');
            return back();
        }
    }
    public function date(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.cash_basis.index')) {
            return $error;
        }
        $period = Period::where('client_id', $client->id)->first();

        if ($client->gst_method != 1) {
            Alert::error('Check your GST method', 'You must check GST method because you are in Accrued');
            return back();
        }
        if (!$period) {
            Alert::error('Period Data Not Found!', 'You must create a period before make report!');
            return redirect()->back();
        }
        return view('admin.reports.cash_basis.date', compact(['client']));
    }
    public function report(Request $request, GstBas $gst)
    {
        if ($error = $this->sendPermissionError('admin.cash_basis.index')) {
            return $error;
        }
        return $data = $gst->consoleCash($request, 'admin.reports.cash_basis.balance');
        // return view('admin.reports.cash_basis.balance', $data);
    }
}
