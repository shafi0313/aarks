<?php

namespace App\Http\Controllers\Frontend\Purchase;

use App\Models\Client;
use App\Mail\BillViewableMail;
use App\Models\Frontend\Creditor;
use \PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class BillReportController extends Controller
{
    // ALl Invoice Report
    public function report($source, $inv_no, Client $client)
    {
        $client   = Client::find(client()->id);
        $invoices = Creditor::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        if ($source == 'item') {
            return view('frontend.purchase.bill-report.item', compact('client', 'source', 'invoices', 'inv_no'));
        }
        // return $invoices;
        return view('frontend.purchase.bill-report.service', compact('client', 'source', 'invoices', 'inv_no'));
    }
    public function print($source, $inv_no, Client $client)
    {
        $client   = Client::find(client()->id);
        $invoices = Creditor::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        // return view('frontend.purchase.bill-report.itemPrint', compact('client', 'source', 'invoices'));
        if ($source == 'item') {
            $pdf = PDF::loadView('frontend.purchase.bill-report.itemPrint', compact('client', 'source', 'invoices'));
        } else {
            $pdf = PDF::loadView('frontend.purchase.bill-report.servicePrint', compact('client', 'source', 'invoices'));
        }
        return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->stream();
    }

    public function mail($source, $inv_no, Client $client)
    {
        $client = Client::find(client()->id);
        $invoices = Creditor::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        $customer = $invoices->first()->customer;
        if ($source == 'item') {
            $pdf = PDF::loadView('frontend.purchase.bill-report.itemPrint', compact('client', 'source', 'invoices'));
        } else {
            $pdf = PDF::loadView('frontend.purchase.bill-report.servicePrint', compact('client', 'source', 'invoices'));
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

        $client = Client::find(client()->id);
        $invoices = Creditor::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        $inv = $invoices->first();
        if (periodLock($client->id, $inv->tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        $customer = $inv->customer;
        // Mail::to('info@aarks.net.au')->queue(new BillViewableMail($src, $inv, $customer, $client));
        Mail::to($customer->email)->send(new BillViewableMail($src, $inv, $customer, $client));
        try {
            toast('Bill Mailed Successful!', 'success');
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
        $invoices = Creditor::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        if ($source == 'item') {
            return view('frontend.purchase.mail-view.bill-item', compact('client', 'source', 'invoices', 'inv_no'));
        }
        // return $invoices;
        return view('frontend.purchase.mail-view.bill-service', compact('client', 'source', 'invoices', 'inv_no'));
    }
}
