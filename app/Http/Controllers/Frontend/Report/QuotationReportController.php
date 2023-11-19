<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use PDF;
use Illuminate\Http\Request;
use App\Mail\QuoteViewableMail;
use App\Mail\InvoiceViewableMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Frontend\DedotrQuoteOrder;

class QuotationReportController extends Controller
{
    public function show($source, $inv_no, Client $client, $customer)
    {
        $client   = Client::find(client()->id);
        $invoices = DedotrQuoteOrder::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('customer_card_id', $customer)
            ->where('inv_no', $inv_no)
            ->get();
        if ($source == 'item') {
            return view('frontend.sales.quote_item.item_quote_show', compact('client', 'source', 'invoices', 'inv_no'));
        } else {
            return view('frontend.sales.quote.service_quote_show', compact('client', 'source', 'invoices', 'inv_no'));
        }
    }

    public function print($source, $inv_no, Client $client)
    {
        $client   = Client::find($client->id);
        $invoices = DedotrQuoteOrder::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        $inv = $invoices->first();
        if ($source == 'item') {
            // return view('frontend.sales.quote.item_quote_print', compact('client', 'source', 'invoices'));
            $pdf = PDF::loadView('frontend.sales.quote_item.item_quote_print', compact('client', 'source', 'invoices'));
        } else {
            $pdf = PDF::loadView('frontend.sales.quote.service_quote_print', compact('client', 'source', 'invoices'));
        }
        return $pdf->setPaper('a4', 'portrait')->setWarnings(false)->stream('quote-invoice-' . $inv_no);
    }

    // Mail
    public function mail($source, $inv_no, Client $client)
    {
        $client = Client::find(client()->id);
        $invoices = DedotrQuoteOrder::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        $inv = $invoices->first();
        $customer = $invoices->first()->customer;
        if ($source == 'item') {
            $pdf = PDF::loadView('frontend.sales.quote.item_quote_print', compact('client', 'source', 'invoices'));
        } elseif ($source == 'email-view') {
            // Mail::to('info@aarks.net.au')->send(new InvoiceViewableMail($inv, $customer, $client));
            Mail::to($customer->email)->send(new InvoiceViewableMail($source, $inv, $customer, $client));
            toast('Invoice email view  successful!', 'success');
            return redirect()->back();
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

    public function viewableMail($src, $inv_no, Client $client)
    {
        $client   = Client::find(client()->id);
        $invoices = DedotrQuoteOrder::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        $inv = $invoices->first();
        $customer = $invoices->first()->customer;
        // Mail::to('msh.shafiul@gmail.com')->queue(new QuoteViewableMail($src, $inv, $customer, $client));
        Mail::to($customer->email)->send(new QuoteViewableMail($src, $inv, $customer, $client));
        try {
            toast('Invoice Mailed Successful!', 'success');
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
        $invoices = DedotrQuoteOrder::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        if ($source == 'item') {
            return view('frontend.sales.mail-view.item_quotation', compact('client', 'source', 'invoices', 'inv_no'));
        }
        // return $invoices;
        return view('frontend.sales.mail-view.service_quotation', compact('client', 'source', 'invoices', 'inv_no'));
    }
}
