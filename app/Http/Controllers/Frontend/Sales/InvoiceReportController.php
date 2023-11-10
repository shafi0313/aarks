<?php

namespace App\Http\Controllers\Frontend\Sales;

use PDF;
use App\Models\Client;
use App\Models\Frontend\Dedotr;
use App\Mail\InvoiceViewableMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class InvoiceReportController extends Controller
{
    // ALl Invoice Report
    public function report($source, $inv_no, Client $client, $customer)
    {
        // return $inv_no;
        $client   = Client::find(client()->id);
        $invoices = Dedotr::with(['client', 'customer','payments' => function ($q) use ($client) {
            return $q->where('client_id', $client->id);
        }])->where('client_id', $client->id)
            ->where('customer_card_id', $customer)
            ->where('inv_no', $inv_no)
            ->get();
        if ($source == 'item') {
            return view('frontend.sales.inv-report.invItem', compact('client', 'source', 'invoices', 'inv_no'));
        }
        // return $invoices;
        return view('frontend.sales.inv-report.invService', compact('client', 'source', 'invoices', 'inv_no'));
    }
    public function print($source, $inv_no, Client $client)
    {
        $client   = Client::find($client->id);
        $invoices = Dedotr::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        $inv = $invoices->first();
        // if (periodLock($client->id, $inv->tran_date)) {
        //     Alert::error('Your enter data period is locked, check administration');
        //     return back();
        // }
        if ($source == 'item') {
            // return view('frontend.sales.inv-report.invItemPrint1', compact('client', 'source', 'invoices'));
            $pdf = PDF::loadView('frontend.sales.inv-report.invItemPrint1', compact('client', 'source', 'invoices'));
        } else {
            $pdf = PDF::loadView('frontend.sales.inv-report.invServicePrint', compact('client', 'source', 'invoices'));
        }
        return $pdf->setPaper('a4', 'portrait')->setWarnings(false)->stream();
    }

    public function mail($source, $inv_no, Client $client)
    {
        $client = Client::find(client()->id);
        $invoices = Dedotr::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        $inv = $invoices->first();
        // if (periodLock($client->id, $inv->tran_date)) {
        //     Alert::error('Your enter data period is locked, check administration');
        //     return back();
        // }
        $customer = $invoices->first()->customer;
        if ($source == 'item') {
            $pdf = PDF::loadView('frontend.sales.inv-report.invItemPrint', compact('client', 'source', 'invoices'));
        } elseif ($source == 'email-view') {
            // Mail::to('info@aarks.net.au')->send(new InvoiceViewableMail($inv, $customer, $client));
            Mail::to($customer->email)->send(new InvoiceViewableMail($source, $inv, $customer, $client));
            toast('Invoice email view  successful!', 'success');
            return redirect()->back();
        } else {
            $pdf = PDF::loadView('frontend.sales.inv-report.invServicePrint', compact('client', 'source', 'invoices'));
        }
        // return $pdf->stream();
        try {
            Mail::send('frontend.sales.payment.mail', ['client'=>$client, 'customer'=>$customer], function ($mail) use ($invoices, $client, $pdf, $customer) {
                $mail->to($customer->email, $customer->email)
                    ->subject('🧾  ' . invoice($invoices->first()->inv_no) . ' Invoice Generated')
                    ->attachData($pdf->output(), invoice($invoices->first()->inv_no) . ".pdf");
            });
            toast('Invoice Mailed Successful!', 'success');
        } catch (\Exception $e) {
            toast('Opps! Server Side Error!', 'error');
            return $e->getMessage();
        }
        return redirect()->back();
    }

    public function viewableMail($src, $inv_no, Client $client)
    {
        $client   = Client::find(client()->id);
        $invoices = Dedotr::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        $inv = $invoices->first();
        if (periodLock($client->id, $inv->tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        $customer = $invoices->first()->customer;
        // Mail::to('info@aarks.net.au')->queue(new InvoiceViewableMail($src, $inv, $customer, $client));
        Mail::to($customer->email)->send(new InvoiceViewableMail($src, $inv, $customer, $client));
        try {
            toast('Invoice Mailed Successful!', 'success');
        } catch (\Exception $e) {
            toast('Opps! Server Side Error!', 'error');
            return $e->getMessage();
        }
        return redirect()->back();
    }
    // ALl Invoice Report Email View
    public function emailViewReport($source, $inv_no, $client)
    {
        $client   = Client::findOrFail(open_decrypt($client));
        $inv_no   = open_decrypt($inv_no);
        $invoices = Dedotr::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        if ($source == 'item') {
            return view('frontend.sales.mail-view.invItem', compact('client', 'source', 'invoices', 'inv_no'));
        }
        // return $invoices;
        return view('frontend.sales.mail-view.invService', compact('client', 'source', 'invoices', 'inv_no'));
    }
}
