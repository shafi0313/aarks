<?php

namespace App\Http\Controllers\Frontend\Purchase;

use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use App\Models\Frontend\Creditor;
use \PDF;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Frontend\CustomerCard;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Frontend\CreditorPaymentReceive;

class CreditorPaymentController extends Controller
{
    public function index()
    {
        $client = Client::find(client()->id);
        $cards =  CustomerCard::with('creditors')
            ->where('client_id', $client->id)
            ->where('type', 2)->get();
        return view('frontend.purchase.payment.index', compact('client', 'cards'));
    }
    public function profession(CustomerCard $customer)
    {
        $client = Client::find($customer->client_id);
        return view('frontend.purchase.payment.select_activity', compact('customer', 'client'));
    }
    public function paymentForm(Request $request, CustomerCard $customer, Profession $profession)
    {
        if ($request->has('open') && $request->open == 'true') {
            return $this->openBalancePayment($customer);
        }
        $client       = Client::find($customer->client_id);
        $liquid_codes = ClientAccountCode::where('additional_category_id', aarks('liquid_asset_id'))
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('code', 'asc')
            ->get();
        $creditors = Creditor::with(['payments' => function ($q) use ($client) {
            return $q->where('client_id', $client->id);
        }])->where('client_id', $client->id)
            ->where('customer_card_id', $customer->id)
            ->where('profession_id', $profession->id)
            ->latest('id')->get();

        if ($client->invoiceLayout->layout == 2) {
            $creditors = $creditors->whereNull('job_title');
        } else {
            $creditors = $creditors->whereNull('item_no');
        }
        if ($creditors->count() <= 0) {
            toast('No Data Found this profession', 'warning');
            return back();
        }

        $openPayment = CreditorPaymentReceive::where('client_id', $client->id)
            ->where('customer_card_id', $customer->id)
            ->where('profession_id', $profession->id)
            ->where('source', $client->invoiceLayout->layout == 2 ? 2 : 1)
            ->where('creditor_inv', null)->sum('payment_amount');
        return view('frontend.purchase.payment.form', compact('customer', 'client', 'liquid_codes', 'creditors', 'profession', 'openPayment'));
    }

    private function openBalancePayment($customer)
    {
        $client       = Client::find($customer->client_id);
        $profession   = Profession::find($customer->profession_id);
        $liquid_codes = ClientAccountCode::where('additional_category_id', aarks('liquid_asset_id'))
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('code', 'asc')
            ->get();
        $creditors = Creditor::with(['payments' => function ($q) use ($client) {
            return $q->where('client_id', $client->id);
        }])->where('client_id', $client->id)
            ->where('customer_card_id', $customer->id)
            ->where('profession_id', $profession->id)
            ->where('amount', '!=', 0)->get();
        $openPayment = CreditorPaymentReceive::where('client_id', $client->id)
            ->where('customer_card_id', $customer->id)
            ->where('profession_id', $profession->id)
            ->where('source', $client->invoiceLayout->layout == 2 ? 2 : 1)
            ->where('creditor_inv', null)->sum('payment_amount');
        // return $dueAmt;
        return view('frontend.purchase.payment.form', compact('customer', 'client', 'liquid_codes', 'creditors', 'profession', 'openPayment'));
        return $customer;
    }
    public function paymentStore(Request $request)
    {
        // return $request;
        $data['tran_date'] = $tran_date = makeBackendCompatibleDate($request->pay_date);
        $data['tax_rate']  = 10;
        $client = Client::find($request->client_id);
        // Check Period Lock
        if (periodLock($request->client_id, $tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        $period = Period::where('client_id', $request->client_id)
            ->where('profession_id', $request->profession_id)
            // ->where('start_date', '<=', $tran_date->format('Y-m-d'))
            ->where('end_date', '>=', $tran_date->format('Y-m-d'))
            ->first();
        DB::beginTransaction();

        if (notEmpty($period)) {
            // $tran_id = $request->client_id . $request->profession_id . $period->id . $tran_date->format('dmy') . rand(11, 99) . '121';
            $tran_id = transaction_id('PBN');

            $data['client_id']        = $request->client_id;
            $data['profession_id']    = $request->profession_id;
            $data['customer_card_id'] = $request->customer_id;
            $data['chart_id']         = $request->bank_account;
            $data['tran_id']          = $tran_id;
            $creditorInv = Creditor::where('client_id', $client->id)
                        ->where('profession_id', $request->profession_id)
                        ->whereIn('inv_no', $request->inv_no)->get();

            foreach ($request->pay_amount as $i => $pay_amount) {
                if ($pay_amount != '') {
                    $data['inv_no']                = str_pad($request->new_inv++, 8, '0', STR_PAD_LEFT);
                    $data['creditor_inv']          = $request->inv_no[$i];
                    $data['payment_amount']        = abs($pay_amount);
                    $data['accum_payment_amount']  = abs($pay_amount) + CreditorPaymentReceive::where('client_id', $client->id)->where('profession_id', $request->profession_id)->where('customer_card_id', $request->customer_id)->max('accum_payment_amount');
                    $data['source']                = $client->invoiceLayout->layout == 2 ? 2 : 1;
                    $cpr = CreditorPaymentReceive::create($data);

                    $gst = [
                        'client_id'          => $request->client_id,
                        'profession_id'      => $request->profession_id,
                        'period_id'          => $period->id,
                        'trn_date'           => $tran_date,
                        'trn_id'             => $tran_id,
                        'source'             => 'PBN',
                        'chart_code'         => $creditorInv->where('inv_no', $request->inv_no[$i])->first()->chart_id,
                        'gross_amount'       => $pay_amount,
                        'gross_cash_amount'  => 0,
                        'gst_accrued_amount' => 0,
                        'gst_cash_amount'    => $pay_amount / 11,
                        'net_amount'         => ($pay_amount - ($pay_amount / 11)),
                    ];
                    Gsttbl::create($gst);

                    // Ledger DATA
                    $caci = ClientAccountCode::where('client_id', $request->client_id)
                        ->where('profession_id', $request->profession_id)->get();
                    $ledger['chart_id']               = $request->bank_account;
                    $ledger['date']                   = $tran_date;
                    $ledger['narration']              = $cpr->customer->name . " PAYMENT FOR ORDER";
                    $ledger['source']                 = 'PBN';
                    $ledger['client_id']              = $request->client_id;
                    $ledger['profession_id']          = $request->profession_id;
                    $ledger['transaction_id']         = $tran_id;
                    $ledger['balance_type']           = 2;
                    $ledger['credit']                 = $ledger['balance'] = $pay_amount;
                    $ledger['debit']                  = 0;
                    $ledger['client_account_code_id'] = $caci->where('code', $request->bank_account)->first()->id;
                    GeneralLedger::create($ledger);

                    $ledger['chart_id']               = 911999;
                    $ledger['balance_type']           = 1;
                    $ledger['client_account_code_id'] = $caci->where('code', 911999)->first()->id;

                    $ledger['debit']  = $pay_amount;
                    $ledger['balance'] = -$pay_amount;
                    $ledger['debit']   = 0;
                    GeneralLedger::create($ledger);
                }
            }

            try {
                DB::commit();
                toast('Payment Received Successful!', 'success');
            } catch (\Exception $e) {
                DB::rollBack();
                toast('Opps! Server Side Error!', 'error');
                // return $e->getMessage();
            }
        } else {
            toast('please check your accounting period from the Accouts>add/editperiod', 'error');
        }
        return redirect()->back();
    }
    public function paymentList()
    {
        $client    = Client::find(client()->id);
        $payments = CreditorPaymentReceive::with('customer')
            ->where('client_id', $client->id)
            ->where('source', $client->invoiceLayout->layout == 2 ? 2 : 1)
            ->get();
        return view('frontend.purchase.payment.list', compact('client', 'payments'));
    }
    public function report(CreditorPaymentReceive $payment)
    {
        $payment->load('client', 'customer');
        return view('frontend.purchase.payment.report', compact('payment'));
    }
    public function printReport(CreditorPaymentReceive $payment)
    {
        $payment->load('client', 'customer');
        // return view('frontend.purchase.payment.print', compact('payment'));
        $pdf = PDF::loadView('frontend.purchase.payment.print', compact('payment'));
        return $pdf->stream();
    }
    public function mailReport(CreditorPaymentReceive $payment)
    {
        $client   = Client::find($payment->client_id);
        $customer = CustomerCard::find($payment->customer_card_id);
        // return view('frontend.purchase.payment.print', compact('payment', 'client', 'customer'));
        $pdf = PDF::loadView('frontend.purchase.payment.print', compact('payment', 'client', 'customer'));
        try {
            Mail::send('frontend.sales.payment.mail', ['client'=>$client, 'customer'=>$customer], function ($mail) use ($payment, $pdf, $customer) {
                $mail->to($customer->email, $customer->email)
                    ->subject('🧾  ' . invoice($payment->inv_no) . ' Bill Payment Receipt')
                    ->attachData($pdf->output(), invoice($payment->inv_no) . ".pdf");
            });
            toast('Payment Mailed Successful!', 'success');
        } catch (\Exception $e) {
            toast('Opps! Server Side Error!', 'error');
            // return $e->getMessage();
        }
        return redirect()->back();
    }

    public function destroy(CreditorPaymentReceive $payment)
    {
        // Check Period Lock
        if (periodLock($payment->client_id, $payment->tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        DB::beginTransaction();

        Gsttbl::where('client_id', $payment->client_id)
        ->where('profession_id', $payment->profession_id)
        ->where('trn_id', $payment->tran_id)
        ->where('source', "PBN")->delete();

        $trade = GeneralLedger::where('client_id', $payment->client_id)
        ->where('profession_id', $payment->profession_id)
        ->where('transaction_id', $payment->tran_id)
        ->where('chart_id', 911999)->first();

        $bank = GeneralLedger::where('client_id', $payment->client_id)
        ->where('profession_id', $payment->profession_id)
        ->where('transaction_id', $payment->tran_id)
        ->where('source', 'PBN')->first();

        $data['balance'] = $balance = $trade->balance + $bank->balance;
        if ($balance < 0) {
            $data['debit']  = abs($balance);
            $data['credit'] = 0;
        } else {
            $data['debit']  = 0;
            $data['credit'] = $balance;
        }
        $trade->update($data);

        GeneralLedger::where('client_id', $payment->client_id)
        ->where('profession_id', $payment->profession_id)
        ->where('transaction_id', $payment->tran_id)
        ->where('source', 'PBN')->delete();

        try {
            $payment->delete();
            DB::commit();
            toast('Payment Deleted Successful!', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            toast('Opps! Server Side Error!', 'error');
            // return $e->getMessage();
        }
        return redirect()->back();
    }
}
