<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use Illuminate\Support\Str;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\Frontend\Dedotr;
use App\Models\ClientPaymentList;
use App\Models\Frontend\Creditor;
use App\Http\Controllers\Controller;
use App\Models\Frontend\DedotrQuoteOrder;
use App\Models\Frontend\CreditorServiceOrder;
use App\Models\Frontend\DedotrPaymentReceive;
use App\Models\Frontend\CreditorPaymentReceive;

class ClientPaymentListController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.client_payment.index')) {
            return $error;
        }
        $paylists = ClientPaymentList::where('status', 0)->where('is_expire', 0)->get();
        return view('admin.client_payment.index', compact('paylists'));
    }

    public function list()
    {
        if ($error = $this->sendPermissionError('admin.client_payment.index')) {
            return $error;
        }
        $paylists = ClientPaymentList::with('subscription')->filter()->latest()->get();
        return view('admin.client_payment.list', compact('paylists'));
    }

    public function pendingDetails(ClientPaymentList $payment)
    {
        if ($error = $this->sendPermissionError('admin.client_payment.index')) {
            return $error;
        }
        return view('admin.client_payment.details_pending', compact('payment'));
    }

    public function edit(ClientPaymentList $payment)
    {
        if ($error = $this->sendPermissionError('admin.client_payment.edit')) {
            return $error;
        }
        // return $payment->expire_at->format('Y-m-d');

        $client       = $payment->client;
        // return DedotrPaymentReceive::whereClientId($client->id)->whereBetween('tran_date', ['2021-10-01', '2021-10-30'])->get();
        $quation      = $payment->sales_quotation - DedotrQuoteOrder::whereClientId($client->id)->where('start_date', '>=', $payment->started_at->format('Y-m-d'))->where('end_date', '<=', $payment->expire_at->format('Y-m-d'))->count();

        $invoice      = $payment->invoice - Dedotr::whereClientId($client->id)->whereBetween('tran_date', [$payment->started_at->format('Y-m-d'), $payment->expire_at->format('Y-m-d')])->count();
        $receipt      = $payment->receipt - DedotrPaymentReceive::whereClientId($client->id)->whereBetween('tran_date', [$payment->started_at->format('Y-m-d'), $payment->expire_at->format('Y-m-d')])->count();
        $bill_quation = $payment->purchase_quotation - CreditorServiceOrder::whereClientId($client->id)->where('start_date', '>=', $payment->started_at->format('Y-m-d'))->where('end_date', '<=', $payment->expire_at->format('Y-m-d'))->count();
        $bill         = $payment->bill - Creditor::whereClientId($client->id)->whereBetween('tran_date', [$payment->started_at->format('Y-m-d'), $payment->expire_at->format('Y-m-d')])->count();
        $bill_payment = $payment->payment - CreditorPaymentReceive::whereClientId($client->id)->whereBetween('tran_date', [$payment->started_at->format('Y-m-d'), $payment->expire_at->format('Y-m-d')])->count();

        $plans = Subscription::get(['id', 'name', 'interval', 'amount']);
        return view('admin.client_payment.edit', compact(['payment', 'client', 'quation', 'invoice', 'receipt', 'bill_quation', 'bill', 'bill_payment', 'plans']));
    }
    public function update(Request $request, ClientPaymentList $paylist)
    {
        if ($error = $this->sendPermissionError('admin.client_payment.edit')) {
            return $error;
        }
        $data = $request->validate([
            "started_at"         => 'required|string',
            "subscription_id"    => 'required|numeric',
            "duration"           => 'required|numeric',
            "amount"             => 'required|numeric',
            "payslip"            => 'nullable|numeric',
            "message"            => 'nullable|string',
            "sales_quotation"    => 'required|numeric',
            "invoice"            => 'required|numeric',
            "receipt"            => 'required|numeric',
            "purchase_quotation" => 'required|numeric',
            "bill"               => 'required|numeric',
            "payment"            => 'required|numeric',
        ]);
        $data['started_at'] = Carbon::createFromFormat('d/m/Y h:i:s', $request->started_at);
        // $data['expire_at']  = Carbon::parse($paylist->expire_at)->addDays($request->duration);
        $data['expire_at']  = now()->addDays($request->duration);
        $rcpt         = $request->file('rcpt');
        if ($request->hasFile('rcpt')) {
            $rcptNew  = "payment_rcpt_" . Str::random(5) . '.' . $rcpt->getClientOriginalExtension();
            if ($rcpt->isValid()) {
                $path = public_path($paylist->rcpt);
                if ($paylist->rcpt && file_exists($path)) {
                    unlink($path);
                }
                $rcpt->storeAs('/rcpt', $rcptNew);
                $data['rcpt']  = '/uploads/rcpt/' . $rcptNew;
            }
        }
        try {
            $paylist->update($data);
            toast('Services Updated!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return redirect()->route('client_payment_index');
    }

    public function status(Request $request, ClientPaymentList $paylist)
    {
        if ($error = $this->sendPermissionError('admin.client_payment.edit')) {
            return $error;
        }
        try {
            ClientPaymentList::whereClientId($paylist->client_id)->whereNot('id', $paylist->id)->update(['is_expire' => 1]);
            $paylist->update(['status' => !$paylist->status]);
            toast('Services Active!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return redirect()->route('client_payment_index');
    }

    public function delete(ClientPaymentList $paylist)
    {
        if ($error = $this->sendPermissionError('admin.client_payment.delete')) {
            return $error;
        }
        try {
            $paylist->delete();
            toast('Payment Deleted!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
}
