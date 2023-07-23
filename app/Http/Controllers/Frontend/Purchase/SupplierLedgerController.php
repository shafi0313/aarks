<?php

namespace App\Http\Controllers\Frontend\Purchase;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Frontend\Creditor;
use App\Http\Controllers\Controller;
use App\Models\Frontend\CustomerCard;
use App\Models\Frontend\CreditorPaymentReceive;

class SupplierLedgerController extends Controller
{
    public function index()
    {
        $client = Client::find(client()->id);
        $customers = CustomerCard::where('client_id', $client->id)->where('type', 2)->get();
        return view('frontend.ledger.supplier.select_activity', compact('client', 'customers'));
    }
    public function show(Request $request)
    {
        $request->validate([
            "client_id"   => "required",
            "customer_id" => "required",
            "from_date"   => "required",
            "to_date"     => "required",
        ]);
        $client     = Client::find($request->client_id);
        $customers  = CustomerCard::where('client_id', $request->client_id)->where('type', 2)->get();
        $start_date = makeBackendCompatibleDate($request->from_date)->format('Y-m-d');
        $end_date   = makeBackendCompatibleDate($request->to_date)->format('Y-m-d');
        $from_date  = $request->from_date;
        $to_date    = $request->to_date;
        $cId        = CustomerCard::find($request->customer_id);

        $creditors = Creditor::where('client_id', $request->client_id)
            ->where('customer_card_id', $request->customer_id)
            ->where('tran_date', '>=', $start_date)
            ->where('tran_date', '<=', $end_date)
            ->where('chart_id', 'not like', '551%')
            ->get();
        if ($client->invoiceLayout->layout == 2) {
            $creditors = $creditors->whereNull('job_title');
            $layout = 'item';
        } else {
            $creditors = $creditors->whereNull('item_no');
            $layout = 'service';
        }

        $payments = CreditorPaymentReceive::where('client_id', $request->client_id)
            ->where('customer_card_id', $request->customer_id)
            ->where('source', $client->invoiceLayout->layout == 2 ? 2 : 1)
            ->where('tran_date', '>=', $start_date)
            ->where('tran_date', '<=', $end_date)
            ->get();
        return view('frontend.ledger.supplier.report', compact('client', 'creditors', 'customers', 'from_date', 'to_date', 'cId', 'payments', 'layout'));
    }
    public function invView($dedotr)
    {
        $dedotrs = Creditor::where('inv_no', $dedotr)->get();
        $dedotr = $dedotrs->first();
        $dedotr->load('customer', 'client');
        return view('frontend.ledger.supplier.inv', compact('dedotr', 'dedotrs'));
    }
    public function payment($dedotr)
    {
        $dedotrs = Creditor::where('inv_no', $dedotr)->get();
        $dedotr = $dedotrs->first();
        $dedotr->load('customer', 'client');
        return view('frontend.ledger.supplier.payment', compact('dedotr', 'dedotrs'));
    }
}
