<?php

namespace App\Http\Controllers\Frontend\Purchase;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Frontend\Creditor;
use \PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class PurchaseReportController extends Controller
{
    // creditor Report ledger
    public function index()
    {
        $client = Client::find(client()->id);
        return view('frontend.ledger.dedotr_report.supplier.index', compact('client'));
    }
    public function report(Request $request)
    {
        $data = $request->validate([
            'end_date'         => 'required',
            'client_id'        => 'required',
        ]);
        $end_date   = $sub = makeBackendCompatibleDate($request->end_date);
        $to_date    = $request->end_date;
        $client     = Client::find($request->client_id);

        $dedotrs = Creditor::with('customer')->where('client_id', $request->client_id)
            ->where('tran_date', '<=', $end_date->format('Y-m-d'))
            ->latest()
            ->get();
        return view('frontend.ledger.dedotr_report.supplier.report', compact([
            'client', 'dedotrs', 'to_date',
        ]));
    }
}
