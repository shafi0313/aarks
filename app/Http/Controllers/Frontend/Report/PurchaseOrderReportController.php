<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use PDF;
use Illuminate\Http\Request;
use App\Mail\OrderViewableMail;
use App\Mail\InvoiceViewableMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Frontend\CreditorServiceOrder;

class PurchaseOrderReportController extends Controller
{
    public function show($source, Client $client, $proId, $customer, $inv_no)
    {
        $client   = Client::find(client()->id);
        $invoices = CreditorServiceOrder::with(['client', 'customer'])
            ->where('client_id', $client->id)
            ->whereProfessionId($proId)
            ->whereCustomerCardId($customer)
            ->where('inv_no', $inv_no)
            ->get();
        if ($source == 'item') {
            return view('frontend.purchase.item.service_item.item_inv_show', compact('client', 'source', 'invoices', 'inv_no'));
        } else {
            return view('frontend.purchase.service_order.service_order_show', compact('client', 'source', 'invoices', 'inv_no'));
        }
    }

    public function print($source, Client $client, $proId, $customer, $inv_no)
    {
        $client   = Client::find($client->id);
        $invoices = CreditorServiceOrder::with(['client', 'customer'])
            ->where('client_id', $client->id)
            ->whereProfessionId($proId)
            ->whereCustomerCardId($customer)
            ->where('inv_no', $inv_no)
            ->get();
        $inv = $invoices->first();
        if ($source == 'item') {
            // return view('frontend.purchase.item.service_item.item_inv_print', compact('client', 'source', 'invoices'));
            $pdf = PDF::loadView('frontend.purchase.item.service_item.item_inv_print', compact('client', 'source', 'invoices'));
        } else {
            $pdf = PDF::loadView('frontend.purchase.service_order.service_order_print', compact('client', 'source', 'invoices'));
        }
        return $pdf->setPaper('a4', 'portrait')->setWarnings(false)->stream('service-order-' . $inv_no);
    }

    // Mail
    public function mail($source, Client $client, $proId, $customer, $inv_no)
    {
        $client   = Client::find(client()->id);
        $invoices = CreditorServiceOrder::with(['client', 'customer'])
            ->where('client_id', $client->id)
            ->whereProfessionId($proId)
            ->whereCustomerCardId($customer)
            ->where('inv_no', $inv_no)
            ->get();
        $inv = $invoices->first();
        $customer = $invoices->first()->customer;
        if ($source == 'item') {
            $pdf = PDF::loadView('frontend.purchase.item.service_item.item_inv_print', compact('client', 'source', 'invoices'));
        } else {
            $pdf = PDF::loadView('frontend.sales.quote.service_quote_print', compact('client', 'source', 'invoices'));
        }

        try {
            Mail::send('frontend.sales.payment.mail', ['client' => $client, 'customer' => $customer], function ($mail) use ($invoices, $client, $pdf, $customer) {
                $mail->to($customer->email, $customer->email)
                    ->subject('🧾  ' . invoice($invoices->first()->inv_no) . ' Quotation')
                    ->attachData($pdf->output(), invoice($invoices->first()->inv_no) . ".pdf");
            });
            toast('Quotation Mailed Successful!', 'success');
        } catch (\Exception $e) {
            toast('Opps! Server Side Error!', 'error');
            return $e->getMessage();
        }
        return redirect()->back();
    }

    public function viewableMail($src, Client $client, $proId, $customer, $inv_no)
    {
        $client   = Client::find(client()->id);
        $invoices = CreditorServiceOrder::with(['client', 'customer'])
            ->where('client_id', $client->id)
            ->whereProfessionId($proId)
            ->whereCustomerCardId($customer)
            ->where('inv_no', $inv_no)
            ->get();
        $inv = $invoices->first();
        $customer = $invoices->first()->customer;
        // Mail::to('msh.shafiul@gmail.com')->queue(new OrderViewableMail($src, $inv, $customer, $client));
        Mail::to($customer->email)->send(new OrderViewableMail($src, $inv, $customer, $client));
        try {
            toast('Order Mailed Successful!', 'success');
        } catch (\Exception $e) {
            toast('Opps! Server Side Error!', 'error');
            return $e->getMessage();
        }
        return redirect()->back();
    }

    // // ALl Invoice Report Email View
    public function emailViewReport($source, $inv_no, $client)
    {
        $client   = Client::findOrFail(open_decrypt($client));
        $inv_no   = open_decrypt($inv_no);
        $invoices = CreditorServiceOrder::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        if ($source == 'item') {
            return view('frontend.sales.mail-view.invItem', compact('client', 'source', 'invoices', 'inv_no'));
        }
        // return $invoices;
        return view('frontend.sales.mail-view.service_quotation', compact('client', 'source', 'invoices', 'inv_no'));
    }
}
