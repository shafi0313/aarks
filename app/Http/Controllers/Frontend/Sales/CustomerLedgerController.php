<?php

namespace App\Http\Controllers\Frontend\Sales;

use PDF;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Frontend\Dedotr;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Frontend\CustomerCard;
use App\Models\Frontend\DedotrPaymentReceive;

class CustomerLedgerController extends Controller
{
    public function index()
    {
        $client    = Client::find(client()->id);
        $customers = CustomerCard::where('client_id', $client->id)->where('type', 1)->orderBy('name')->get();
        return view('frontend.ledger.customer_ledger.select_activity', compact('client', 'customers'));
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
        $customers  = CustomerCard::where('client_id', $request->client_id)->where('type', 1)->orderBy('name')->get();
        $start_date = makeBackendCompatibleDate($request->from_date)->format('Y-m-d');
        $end_date   = makeBackendCompatibleDate($request->to_date)->format('Y-m-d');
        $from_date  = $request->from_date;
        $to_date    = $request->to_date;
        $cId        = CustomerCard::find($request->customer_id);

        $dedotrs = Dedotr::where('client_id', $request->client_id)
            ->where('customer_card_id', $request->customer_id)
            ->where('tran_date', '>=', $start_date)
            ->where('tran_date', '<=', $end_date)
            ->where('chart_id', 'not like', '551%')
            ->select('*', DB::raw('sum(amount) as inv_amt'))
            ->orderBy('tran_date')->groupBy('inv_no')
            ->get();
        if ($client->invoiceLayout->layout == 2) {
            $dedotrs = $dedotrs->whereNull('job_title');
            $layout = 'item';
        } else {
            $dedotrs = $dedotrs->whereNull('item_no');
            $layout = 'service';
        }
        $payments = DedotrPaymentReceive::where('client_id', $client->id)
            ->where('customer_card_id', $cId->id)
            ->where('source', $client->invoiceLayout->layout == 2 ? 2 : 1)
            ->where('tran_date', '>=', $start_date)
            ->where('tran_date', '<=', $end_date)
            ->orderBy('tran_date')
            ->get();

        $pdf = PDF::loadView('frontend.ledger.customer_ledger.print_report', compact('client', 'dedotrs', 'customers', 'from_date', 'to_date', 'cId', 'payments', 'layout'));
        if ($request->submit == 'Print') {
            return $pdf->stream();
        } elseif ($request->submit == 'Email') {
            try {
                Mail::send('frontend.sales.payment.mail', ['client'=>$client, 'customer'=>$cId], function ($mail) use ($pdf, $cId) {
                    $mail->to($cId->email, $cId->email)
                        ->subject('🧾 Customer Ledger Generated')
                        ->attachData($pdf->output(), transaction_id(16) . ".pdf");
                });
                toast('Customer Ledger Mailed Successful!', 'success');
            } catch (\Exception $e) {
                toast('Opps! Server Side Error!', 'error');
                return $e->getMessage();
            }
            return redirect()->back();
        }
        return view('frontend.ledger.customer_ledger.customer_report', compact('client', 'dedotrs', 'customers', 'from_date', 'to_date', 'cId', 'payments', 'layout'));
    }
    public function invView($dedotr)
    {
        $dedotrs = Dedotr::where('inv_no', $dedotr)->get();
        $dedotr = $dedotrs->first();
        $dedotr->load('customer', 'client');
        return view('frontend.ledger.customer_ledger.inv', compact('dedotr', 'dedotrs'));
    }
    public function payment($dedotr)
    {
        $dedotrs = DedotrPaymentReceive::where('id', $dedotr)->get();
        $dedotr = $dedotrs->first();
        $dedotr->load('customer', 'client');
        return view('frontend.ledger.customer_ledger.payment', compact('dedotr', 'dedotrs'));
    }
}
