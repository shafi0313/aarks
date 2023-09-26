<?php

namespace App\Http\Controllers\Frontend\Sales;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Frontend\CustomerCard;
use App\Models\Frontend\InvoiceLayout;

class DedotorReportController extends Controller
{
    // Dedotrs ledger
    public function index()
    {
        $client = Client::find(client()->id);
        return view('frontend.ledger.dedotr_report.customer.index', compact('client'));
    }
    public function report(Request $request)
    {
        $data = $request->validate([
            'end_date'         => 'required',
            'client_id'        => 'required',
        ]);
        $end_date   = $sub = makeBackendCompatibleDate($request->end_date);
        $to_date    = $request->end_date;
        $client     = Client::find(client()->id);
        $layout     = InvoiceLayout::whereClientId($client->id)->first();

        if ($layout->layout == 1) {
            $customers = CustomerCard::where('client_id', $request->client_id)
                ->with([
                    'dedotrPayments' => fn ($payment) => $payment->select('id','dedotr_inv','client_id','customer_card_id','profession_id','tran_date','payment_amount')
                        ->where('client_id', $client->id)
                        ->where('tran_date', '<=', $end_date->format('Y-m-d')),
                    'dedotrs' => fn ($q) => $q->select('id','client_id','customer_card_id','profession_id','tran_date','amount')
                        ->where('client_id', $client->id)
                        ->where('tran_date', '<=', $end_date->format('Y-m-d'))
                        // ->whereNotNull('job_title')
                ])
                ->orderBy('name')->get();
        } else {
            $customers = CustomerCard::where('client_id', $request->client_id)
                ->select('id','client_id','profession_id','name','opening_blnc')
                ->with([
                    'dedotrPayments' => fn ($payment) => $payment->select('id','dedotr_inv','client_id','customer_card_id','profession_id','tran_date','payment_amount')
                        ->where('client_id', $client->id)
                        ->where('tran_date', '<=', $end_date->format('Y-m-d')),
                    'dedotrs' => fn ($q) => $q->select('id','client_id','customer_card_id','profession_id','tran_date','amount')
                        ->where('client_id', $client->id)
                        ->where('tran_date', '<=', $end_date->format('Y-m-d'))
                        // ->whereNotNull('item_no')
                ])
                ->orderBy('name')->get();
        }
        return view('frontend.ledger.dedotr_report.customer.report', compact([
            'client', 'customers', 'to_date',
        ]));
    }
}
