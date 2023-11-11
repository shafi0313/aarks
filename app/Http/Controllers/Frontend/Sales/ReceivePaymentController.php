<?php

namespace App\Http\Controllers\Frontend\Sales;

use PDF;
use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\Frontend\Dedotr;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Frontend\CustomerCard;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Frontend\DedotrPaymentReceive;
use App\Models\Frontend\DedotrTempPaymentReceive;

class ReceivePaymentController extends Controller
{
    public function index()
    {
        $client = Client::find(client()->id);
        $cards  = CustomerCard::with('dedotrs')
        ->where('client_id', $client->id)
        ->where('type', 1)->get();
        return view('frontend.sales.payment.index', compact('client', 'cards'));
    }
    public function profession(CustomerCard $customer)
    {
        $client = Client::find($customer->client_id);
        return view('frontend.sales.payment.select_activity', compact('customer', 'client'));
    }
    public function paymentForm(Request $request, CustomerCard $customer, Profession $profession)
    {
        if ($request->has('open') && $request->open == 'true') {
            return $this->openBalancePayment($customer);
        }
        $client = Client::find($customer->client_id);
        $liquid_codes = ClientAccountCode::where('additional_category_id', aarks('liquid_asset_id'))
                    ->where('client_id', $client->id)
                    ->where('profession_id', $profession->id)
                    ->where('code', '!=', 551800)
                    ->orderBy('code', 'asc')
                    ->get();
        $dedotrs = Dedotr::with(['payments'=>function ($q) use ($client, $profession, $customer) {
            $q->where('client_id', $client->id)
                ->where('customer_card_id', $customer->id)
                ->where('profession_id', $profession->id);
        }])->where('client_id', $client->id)
        ->where('customer_card_id', $customer->id)
        ->where('profession_id', $profession->id)
        ->where('amount', '!=', 0)->get();

        if ($client->invoiceLayout->layout == 2) {
            $dedotrs = $dedotrs->whereNull('job_title');
        } else {
            $dedotrs = $dedotrs->whereNull('item_no');
        }
        if ($dedotrs->count() <= 0) {
            toast('No Data Found this profession', 'warning');
            return back();
        }
        $openPayment = DedotrPaymentReceive::where('client_id', $client->id)
                ->where('customer_card_id', $customer->id)
                ->where('profession_id', $profession->id)
                ->where('source', $client->invoiceLayout->layout == 2?2:1)
                ->where('dedotr_inv', null)->sum('payment_amount');
        return view('frontend.sales.payment.form', compact('customer', 'client', 'liquid_codes', 'dedotrs', 'profession', 'openPayment'));
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
        $dedotrs = Dedotr::with(['payments'=>function ($q) use ($client, $profession, $customer) {
            $q->where('client_id', $client->id)
                ->where('customer_card_id', $customer->id)
                ->where('profession_id', $profession->id);
        }])->where('client_id', $client->id)
        ->where('customer_card_id', $customer->id)
        ->where('profession_id', $profession->id)
        ->where('amount', '!=', 0)->get();
        $openPayment = DedotrPaymentReceive::where('client_id', $client->id)
                ->where('customer_card_id', $customer->id)
                ->where('profession_id', $profession->id)
                ->where('source', $client->invoiceLayout->layout == 2?2:1)
                ->where('dedotr_inv', null)->sum('payment_amount');
        // return $dueAmt;
        return view('frontend.sales.payment.form', compact('customer', 'client', 'liquid_codes', 'dedotrs', 'profession', 'openPayment'));
        return $customer;
    }
    public function paymentStore(Request $request)
    {
        $data ['tran_date'] = $tran_date = makeBackendCompatibleDate($request->pay_date);
        $data ['tax_rate']  = 10;
        $client = Client::find($request->client_id);
        // Check Period Lock
        if (periodLock($client->id, $tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        $period = Period::where('client_id', $client->id)
                ->where('profession_id', $request->profession_id)
                // ->where('start_date', '<=', $tran_date->format('Y-m-d'))
                ->where('end_date', '>=', $tran_date->format('Y-m-d'))
                ->first();

        DB::beginTransaction();
        if (notEmpty($period)) {
            // $tran_id = $client->id.$request->profession_id.$period->id.$tran_date->format('dmy').rand(11, 999);
            $tran_id = transaction_id('PIN');
            $data['client_id']        = $client->id;
            $data['profession_id']    = $request->profession_id;
            $data['customer_card_id'] = $request->customer_id;
            $data['chart_id']         = $request->bank_account;
            $data['tran_id']          = $tran_id;
            $dedotrInv = Dedotr::where('client_id', $client->id)
                        ->where('profession_id', $request->profession_id)
                        ->whereIn('inv_no', $request->inv_no)->get();
            foreach ($request->pay_amount as $i => $pay_amount) {
                if (notEmpty($pay_amount)) {
                    $data['inv_no']                = str_pad($request->new_inv++, 8, '0', STR_PAD_LEFT);
                    $data['dedotr_inv']            = $request->inv_no[$i];
                    $data ['payment_amount']       = abs($pay_amount);
                    $data ['accum_payment_amount'] = abs($pay_amount) + DedotrPaymentReceive::where('client_id', $client->id)->where('profession_id', $request->profession_id)->where('customer_card_id', $request->customer_id)->max('accum_payment_amount');
                    $data['source'] = $client->invoiceLayout->layout == 2?2:1;
                    $dpr = DedotrPaymentReceive::create($data);
                    DedotrTempPaymentReceive::create($data);
                    $gst = [
                        'client_id'          => $client->id,
                        'profession_id'      => $request->profession_id,
                        'period_id'          => $period->id,
                        'trn_date'           => $tran_date,
                        'trn_id'             => $tran_id,
                        'source'             => 'PIN',
                        'chart_code'         => optional($dedotrInv->where('inv_no', $request->inv_no[$i])->first())->chart_id??552100,
                        'gross_amount'       => $pay_amount,
                        'gross_cash_amount'  => 0,
                        'gst_accrued_amount' => 0,
                        'gst_cash_amount'    => $pay_amount/11,
                        'net_amount'         => ($pay_amount - ($pay_amount/11)),
                    ];
                    Gsttbl::create($gst);

                    // Ledger DATA
                    $caci = ClientAccountCode::where('client_id', $client->id)
                            ->where('profession_id', $request->profession_id)->get();
                    $ledger['chart_id']               = $request->bank_account;
                    $ledger['date']                   = $tran_date;
                    $ledger['narration']              = $dpr->customer->name." PAYMENT FOR INV";
                    $ledger['source']                 = 'PIN';
                    $ledger['client_id']              = $client->id;
                    $ledger['profession_id']          = $request->profession_id;
                    $ledger['transaction_id']         = $tran_id;
                    $ledger['balance_type']           = 1;
                    $ledger['debit']                  = $ledger['balance'] = $pay_amount;
                    $ledger['credit']                 = 0;
                    $ledger['client_account_code_id'] = $caci->where('code', $request->bank_account)->first()->id;
                    GeneralLedger::create($ledger);
                    $ledger['chart_id']               = 552100;
                    $ledger['balance_type']           = 1;
                    $ledger['client_account_code_id'] = $caci->where('code', 552100)->first()->id;

                    $ledger['credit']  = $pay_amount;
                    $ledger['balance'] = - $pay_amount;
                    $ledger['debit']   = 0;
                    GeneralLedger::create($ledger);
                }
            }

            try {
                DB::commit();
                toast('Payment Success!', 'success');
            } catch (\Exception $e) {
                DB::rollBack();
                toast('Opps! Server Side Error!', 'error');
                // toast($e->getMessage(), 'error');
            }
        } else {
            toast('please check your accounting period from the Accouts>add/editperiod', 'error');
        }
        return back();
    }
    public function paymentList()
    {
        $client    = Client::find(client()->id);
        $payments = DedotrPaymentReceive::with('customer')
        ->where('client_id', $client->id)
        ->where('source', $client->invoiceLayout->layout == 2?2:1)
        ->get();
        return view('frontend.sales.payment.list', compact('client', 'payments'));
    }
    public function report(DedotrPaymentReceive $payment)
    {
        $payment->load('client', 'customer');
        return view('frontend.sales.payment.report', compact('payment'));
    }
    public function reportPrint(DedotrPaymentReceive $payment)
    {
        $payment->load('client', 'customer');
        // return view('frontend.sales.payment.print', compact('payment'));
        $pdf = PDF::loadView('frontend.sales.payment.print', compact('payment'));
        return $pdf->stream();
    }
    public function reportMail(DedotrPaymentReceive $payment)
    {
        $client   = Client::find($payment->client_id);
        // if (periodLock($client->id, $payment->tran_date)) {
        //     Alert::error('Your enter data period is locked, check administration');
        //     return back();
        // }
        $customer = CustomerCard::find($payment->customer_card_id);
        // return view('frontend.sales.payment.print', compact('payment', 'client', 'customer'));
        $pdf = PDF::loadView('frontend.sales.payment.print', compact('payment', 'client', 'customer'));
        try {
            Mail::send('frontend.sales.payment.mail', ['client'=>$client, 'customer'=>$customer], function ($mail) use ($payment, $pdf, $customer) {
                $mail->to($customer->email, $customer->email)
                    ->subject('🧾  ' . invoice($payment->inv_no) . ' Invoice Payment Receipt')
                    ->attachData($pdf->output(), invoice($payment->inv_no) . ".pdf");
            });
            toast('Payment Mailed Successful!', 'success');
        } catch (\Exception $e) {
            // toast('Opps! Server Side Error!', 'error');
            return $e->getMessage();
        }
        return redirect()->back();
    }
    public function destroy(DedotrPaymentReceive $payment)
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
        ->where('source', "PIN")->delete();

        $trade = GeneralLedger::where('client_id', $payment->client_id)
        ->where('profession_id', $payment->profession_id)
        ->where('transaction_id', $payment->tran_id)
        ->where('chart_id', 552100)->first();

        $bank = GeneralLedger::where('client_id', $payment->client_id)
        ->where('profession_id', $payment->profession_id)
        ->where('transaction_id', $payment->tran_id)
        ->where('source', 'PIN')->first();

        $data['balance'] = $balance = optional($trade)->balance + optional($bank)->balance;
        if ($balance > 0) {
            $data['debit']  = $balance;
            $data['credit'] = 0;
        } else {
            $data['debit']  = 0;
            $data['credit'] = abs($balance);
        }
        if ($trade) {
            $trade->update($data);
        }
        // $bank->delete();

        GeneralLedger::where('client_id', $payment->client_id)
        ->where('profession_id', $payment->profession_id)
        ->where('transaction_id', $payment->tran_id)
        ->where('source', 'PIN')->delete();

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
