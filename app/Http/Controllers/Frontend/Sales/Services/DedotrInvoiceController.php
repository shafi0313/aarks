<?php

namespace App\Http\Controllers\Frontend\Sales\Services;

use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Actions\RetainEarning;
use App\Models\Frontend\Dedotr;
use App\Models\CustomerTempInfo;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Frontend\CustomerCard;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\DedotrQuoteRequest;
use App\Models\Frontend\DedotrPaymentReceive;

class DedotrInvoiceController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.sales.invoice.select_activity', compact('client'));
    }
    public function quote(Profession $profession)
    {
        $client  = Client::find(client()->id);
        $payment  = $client->payment;
        $quation = Dedotr::whereClientId($client->id)->whereBetween('tran_date', [$payment->started_at->format('Y-m-d'), $payment->expire_at->format('Y-m-d')])->count();
        if ($quation > $payment->invoice) {
            toast('Invoice limit reached.', 'error');
            return redirect()->back();
        }
        $customers = CustomerCard::where('client_id', $client->id)->where('profession_id', $profession->id)->where('type', 1)->orderBy('name')->get();
        $codes     = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', 'like', '1%')
            ->where('type', '2')->orderBy('code')
            ->get();

        $liquid_codes = ClientAccountCode::where('additional_category_id', aarks('liquid_asset_id'))
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('code', 'asc')
            ->get();

        return view('frontend.sales.invoice.service_invoice', compact('client', 'customers', 'codes', 'profession', 'liquid_codes'));
    }

    public function customerList()
    {
        $client = Client::find(client()->id);
        $quotes = Dedotr::with('customer')->where('client_id', $client->id)->get()->groupBy('customer_card_id');
        return view('frontend.sales.invoice.customer_list', compact('client', 'quotes'));
    }
    public function manage()
    {
        $client   = client();
        $invoices = Dedotr::filter()->selectRaw('id, tran_date,tran_id,inv_no,amount,customer_card_id, sum(amount) as totalAmt')->with(['payments' => function ($q) use ($client) {
            return $q->select('id', 'dedotr_inv', 'payment_amount')->where('client_id', $client->id);
        }, 'customer' => fn ($q) => $q->select('id', 'name')])->where('client_id', $client->id)
            ->where('chart_id', 'not like', '551%')
            ->where('job_title', '!=', '')
            ->groupBy(['tran_id'])
            // ->groupBy(['customer_card_id','inv_no'])
            ->get();
        return view('frontend.sales.invoice.manage', compact('client', 'invoices'));
    }
    public function store(DedotrQuoteRequest $request)
    {
        $data = $request->validated();

        $client = Client::find($request->client_id);
        if ($request->due_date) {
            $data['due_date'] = $tran_date = makeBackendCompatibleDate($request->due_date);
        }
        $data['tran_date'] = $tran_date = makeBackendCompatibleDate($request->start_date);
        if (periodLock($request->client_id, $tran_date)) {
            return response()->json('Your enter data period is locked, check administration', 500);
        }
        $period = Period::where('client_id', $client->id)
            ->where('profession_id', $request->profession_id)
            // ->where('start_date', '<=', $tran_date->format('Y-m-d'))
            ->where('end_date', '>=', $tran_date->format('Y-m-d'))
            ->first();

        $chkOverhead = ClientAccountCode::where('client_id', $request->client_id)
            ->where('profession_id', $request->profession_id)
            ->where('code', 191295)
            ->first();
        $chkRate = ClientAccountCode::where('client_id', $request->client_id)
            ->where('profession_id', $request->profession_id)
            ->where('code', 191998)
            ->first();
        DB::beginTransaction();

        if ($request->has('name') && $request->name != '') {
            $one_of = $request->validate([
                'client_id'        => 'required',
                'profession_id'    => 'required',
                'customer_card_id' => 'required',
                "inv_no"           => 'required',
                'name'             => 'required|string',
                'email'            => 'nullable|email',
                'phone'            => 'nullable|numeric',
                'address'          => 'required|string',
                'city'             => 'required|string',
                'state'            => 'required|string',
            ]);
            $chk_card = CustomerCard::whereClientId($client->id)->whereProfessionId($request->profession_id)->whereCustomerType('default')->first();
            if ($chk_card) {
                CustomerTempInfo::create($one_of);
            }
        }
        if ($request->bank_account != '' && $request->payment_amount > 0) {
            //Payment Amount
            $data['payment_amount']       = abs($request->payment_amount);
            $data['accum_payment_amount'] = abs($request->payment_amount) + DedotrPaymentReceive::where('client_id', $client->id)->where('profession_id', $request->profession_id)->where('customer_card_id', $request->customer_card_id)->max('accum_payment_amount');
        }

        // if ($request->ajax() && $period) {
        // $tran_id = $client->id . $request->profession_id . $period->id. $request->customer_card_id . $tran_date->format('dmy') . rand(11, 99);
        $inv_no = Dedotr::whereClientId($request->client_id)->whereProfessionId($request->profession_id)->max('inv_no') + 1;
        $tran_id = transaction_id('INV');

        // Transaction Id duplicity check
        if (Dedotr::whereTran_id($tran_id)->count() > 0 || Dedotr::whereClientId($request->client_id)->whereInv_no($inv_no)->count() > 0) {
            $message = ['status' => 406, 'message' => 'Something went wrong. Please try again.'];
            return response()->json($message);
        }

        foreach ($request->chart_id as $i => $chart_id) {
            $data['chart_id']       = $chart_id;
            $data['tran_id']        = $tran_id;
            $data['inv_no']         = $inv_no;
            $data['disc_rate']      = $request->disc_rate[$i];
            $data['disc_amount']    = $request->disc_amount[$i];
            $data['freight_charge'] = $request->freight_charge[$i];
            $data['is_tax']         = $request->is_tax[$i];
            $data['tax_rate']       = $request->tax_rate[$i];
            $data['job_des']        = $request->job_des[$i];
            $data['job_title']      = $request->job_title[$i];
            $data['price']          = $request->price[$i];
            $data['amount']         = $request->totalamount[$i];
            $data['accum_amount']   = $request->totalamount[$i] + Dedotr::where('client_id', $client->id)->where('profession_id', $request->profession_id)->where('customer_card_id', $request->customer_card_id)->sum('amount');

            Dedotr::create($data);
        }

        if ($request->bank_account != '' && $request->payment_amount > 0) {
            //Payment Amount
            $payment      = $data;
            $payment['chart_id']   = $request->bank_account;
            $payment['dedotr_inv'] = $request->inv_no;
            $payment['source']     = $client->invoiceLayout->layout == 2 ? 2 : 1;

            DedotrPaymentReceive::create($payment);
        }
        $dedotrs = Dedotr::where('client_id', $client->id)
            ->where('tran_id', $tran_id)
            ->whereIn('chart_id', $request->chart_id)
            ->orderBy('chart_id')
            ->get()->groupBy('chart_id');
        $gst = [
            'client_id'          => $client->id,
            'profession_id'      => $request->profession_id,
            'period_id'          => $period->id,
            'trn_date'           => $tran_date,
            'trn_id'             => $tran_id,
            'source'             => 'PIN',
            'chart_code'         => $request->chart_id[0],
            'gross_amount'       => $request->payment_amount,
            'gross_cash_amount'  => 0,
            'gst_accrued_amount' => 0,
            'gst_cash_amount'    => $request->payment_amount / 11,
            'net_amount'         => ($request->payment_amount - ($request->payment_amount / 11)),
        ];
        if ($request->bank_account != '' && $request->payment_amount != 0) {
            Gsttbl::create($gst);
        }
        foreach ($dedotrs as $dedotr) {
            $gst['gross_cash_amount'] = $gst['gst_cash_amount'] = 0;
            $gst['chart_code']        = $dedotr->first()->chart_id;
            $amount                   = $dedotr->sum('amount');
            $price                    = $dedotr->sum('price');
            $disc_rate                = $dedotr->sum('disc_rate') / $dedotr->count();
            $freight_charge           = $dedotr->sum('freight_charge');
            $gst['source']            = 'INV';
            if ($dedotr->first()->is_tax == 'yes') {
                $fPrice          = $price + ($price * 0.1);
                $pgst            = $price * 0.1;
                $fDisc_rate      = $price * ($disc_rate / 100) + (($price * ($disc_rate / 100)) * 0.1);
                $dgst            = ($price * ($disc_rate / 100)) * 0.1;
                $fFreight_charge = $freight_charge + ($freight_charge * 0.1);
                $fgst            = $freight_charge * 0.1;
            } else {
                $fPrice          = $price;
                $pgst            = 0;
                $fDisc_rate      = $price * ($disc_rate / 100);
                $dgst            = 0;
                $fFreight_charge = $freight_charge;
                $fgst            = 0;
            }
            $gst['gross_amount']       = $fPrice;
            $gst['gst_accrued_amount'] = $pgst;
            $gst['net_amount']         = $fPrice - $pgst;

            $checksGst = Gsttbl::where('client_id', $client->id)->where('trn_id', $tran_id)->where('source', 'INV')->get();

            $checkGst = $checksGst->where('chart_code', $dedotr->first()->chart_id)->first();
            if ($checkGst != '') {
                $checkGst->update($gst);
            } else {
                Gsttbl::create($gst);
            }
            if ($chkOverhead && $dedotr->first()->freight_charge) {
                $gst['gross_amount']       = $fFreight_charge;
                $gst['gst_accrued_amount'] = $fgst;
                $gst['net_amount']         = $fFreight_charge - $fgst;
                if ($dedotr->first()->is_tax == 'yes') {
                    $gst['chart_code'] = 191295;
                    $checkfr      = $checksGst->where('chart_code', 191295)->first();
                    if ($checkfr != '') {
                        $gst['gross_amount']       = $checkfr->gross_amount + $fFreight_charge;
                        $gst['gst_accrued_amount'] = $checkfr->gst_accrued_amount + $fgst;
                        $gst['net_amount']         = $checkfr->net_amount + $fFreight_charge - $fgst;
                        $checkfr->update($gst);
                    } else {
                        Gsttbl::create($gst);
                    }
                } else {
                    $gst['chart_code'] = 191296;
                    $checkfr      = $checksGst->where('chart_code', 191296)->first();
                    if ($checkfr != '') {
                        $gst['gross_amount']       = $checkfr->gross_amount + $fFreight_charge;
                        $gst['gst_accrued_amount'] = $checkfr->gst_accrued_amount + $fgst;
                        $gst['net_amount']         = $checkfr->net_amount + $fFreight_charge - $fgst;
                        $checkfr->update($gst);
                    } else {
                        Gsttbl::create($gst);
                    }
                }
            }

            if ($chkRate && $dedotr->first()->disc_rate) {
                $gst['gross_amount']       = -$fDisc_rate;
                $gst['gst_accrued_amount'] = -$dgst;
                $gst['net_amount']         = -$fDisc_rate + $dgst;
                if ($dedotr->first()->is_tax == 'yes') {
                    $gst['chart_code'] = 191998;
                    $checkdis     = $checksGst->where('chart_code', 191998)->first();
                    if ($checkdis != '') {
                        $gst['gross_amount']       = $checkdis->gross_amount - $fDisc_rate;
                        $gst['gst_accrued_amount'] = $checkdis->gst_accrued_amount - $dgst;
                        $gst['net_amount']         = $checkdis->net_amount - $fDisc_rate + $dgst;
                        $checkdis->update($gst);
                    } else {
                        Gsttbl::create($gst);
                    }
                } else {
                    $gst['chart_code'] = 191999;
                    $checkdis     = $checksGst->where('chart_code', 191999)->first();
                    if ($checkdis != '') {
                        $gst['gross_amount']       = $checkdis->gross_amount - $fDisc_rate;
                        $gst['gst_accrued_amount'] = $checkdis->gst_accrued_amount - $dgst;
                        $gst['net_amount']         = $checkdis->net_amount - $fDisc_rate + $dgst;
                        $checkdis->update($gst);
                    } else {
                        Gsttbl::create($gst);
                    }
                }
            }
        }
        // Ledger DATA
        $ledger['date']           = $tran_date;
        $ledger['narration']      = $dedotr->first()->customer->name;
        $ledger['source']         = 'INV';
        $ledger['client_id']      = $cid              = $client->id;
        $ledger['profession_id']  = $pid              = $request->profession_id;
        $ledger['transaction_id'] = $tran_id;
        $ledger['balance_type']   = 2;
        $ledger['debit']          = $ledger['credit'] = 0;


        $gstData = Gsttbl::where('client_id', $client->id)
            ->where('trn_id', $tran_id)
            ->where('trn_date', $tran_date->format('Y-m-d'))
            ->orderBy('chart_code')
            ->get();

        $codes = ClientAccountCode::where('client_id', $cid)
            ->where('profession_id', $pid)
            ->get();
        // Account code from INV
        foreach ($gstData->where('source', 'INV') as $gd) {
            $code                     = $codes->where('code', $gd->chart_code)->first();
            $ledger['chart_id']               = $code->code;
            $ledger['client_account_code_id'] = $code->id;
            $ledger['balance']                = abs($gd->net_amount);
            $ledger['gst']                    = abs($gd->gst_accrued_amount);
            if ($code->code == 191998 || $code->code == 191999) {
                $ledger['debit']        = abs($gd->gross_amount);
                $ledger['credit']       = 0;
                $ledger['balance_type'] = 1;
            } else {
                $ledger['credit'] = abs($gd->gross_amount);
                $ledger['debit']  = 0;
            }
            GeneralLedger::create($ledger);
        }
        // Trade Debotor code
        $trade                    = $codes->where('code', 552100)->first();
        $ledger['balance_type']           = 1;
        $ledger['chart_id']               = $trade->code;
        $ledger['client_account_code_id'] = $trade->id;
        $ledger['balance']                = $ledger['debit'] = $request->total_amount - $request->payment_amount;
        $ledger['credit']                 = $ledger['gst']   = 0;
        GeneralLedger::create($ledger);
        // Gst Payable code
        $gstpay                   = $codes->where('code', 912100)->first();
        $ledger['balance_type']           = 2;
        $ledger['chart_id']               = $gstpay->code;
        $ledger['client_account_code_id'] = $gstpay->id;
        $ledger['balance']                = $ledger['credit'] = $request->gst_amt_subtotal;
        $ledger['debit']                  = $ledger['gst']    = 0;
        GeneralLedger::create($ledger);
        // payment received code or bank AC
        $bankAC = $gstData->where('source', 'PIN')->first();
        if (!empty($bankAC) && $request->payment_amount != '' && $request->bank_account != '') {
            $ledger['chart_id']               = $request->bank_account;
            $ledger['debit']                  = $ledger['balance'] = $bankAC->gross_amount;
            $ledger['gst']                    = 0;
            $ledger['balance_type']           = 1;
            $ledger['client_account_code_id'] = $codes->where('code', $request->bank_account)->first()->id;
            $ledger['credit']                 = 0;
            $ledger['source']                 = 'PIN';
            $ledger['narration']              = $dedotr->first()->customer->name . ' INV Payment';
            GeneralLedger::create($ledger);
        }

        //RetailEarning Calculation
        RetainEarning::retain($cid, $pid, $tran_date, $ledger, ['INV', 'INV']);

        // Retain Earning For each Transaction
        RetainEarning::tranRetain($cid, $pid, $tran_id, $ledger, ['INV', 'INV']);

        if ($request->ajax() && $period) {
            DB::commit();
            $toast   = 'Invoice Create successfully';
            $message = ['status' => 200, 'message' => $toast, 'inv_no' => Dedotr::whereClientId($cid)->whereProfessionId($pid)->max('inv_no') + 1];
            return response()->json($message);
        } elseif (!$request->ajax() && $period) {
            DB::commit();
            return redirect()->route('inv.report', ['service', $request->inv_no, $client->id, $request->customer_card_id]);
        } elseif (!$request->ajax() && !$period) {
            $toast = 'Please check your accounting period from the Accounts>add/edit period';
            Alert::info($toast);
            return back();
        } else {
            $toast   = 'Please check your accounting period from the Accounts>add/edit period';
            $message = ['status' => 500, 'message' => $toast];
            DB::commit();
            return response()->json($message);
        }
        // if (!$request->ajax() && $period) {
        //     return redirect()->route('inv.report', ['service', $request->inv_no, $client->id, $request->customer_card_id]);
        // }else{
        //     $toast = 'Please check your accounting period from the Accounts>add/edit period';
        //     Alert::info($toast);
        //     return back();
        // }

        try {
            // DB::commit();
            // $toast   = 'Invoice Create successfully';
            // $message = ['status' => 200, 'message' => $toast, 'inv_no' => Dedotr::whereClientId($cid)->whereProfessionId($pid)->max('inv_no') + 1];
            // return response()->json($message);
        } catch (\Exception $e) {
            DB::rollBack();
            $toast   = $e->getMessage();
            $message = ['status' => 500, 'message' => $toast];
            return response()->json($message);
        }
        // return response()->json($message);
    }
    public function edit(Request $request, $inv_no, Client $client, $customer)
    {
        $invoices = Dedotr::with(['client', 'customer'])
            ->where('client_id', $client->id)
            ->where('customer_card_id', $customer)
            ->where('job_title', '!=', '')
            ->where('inv_no', $inv_no)
            ->get();
        $invoice   = $invoices->first();
        $profession = Profession::findOrFail($invoice->profession_id);
        if (periodLock($client->id, $invoice->tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        if ($request->ajax()) {
            return response()->json(['invoices' => $invoices, 'status' => 200]);
        }
        if ($invoices->count() > 0) {
            $client    = Client::find($invoice->client_id);
            $customers = CustomerCard::where('client_id', $client->id)->get();
            $codes     = ClientAccountCode::where('client_id', $invoice->client_id)
                ->where('profession_id', $invoice->profession_id)
                ->where(function ($q) {
                    $q->where('code', 'like', '1%')
                        ->orWhere('code', 'like', '2%')
                        ->orWhere('code', 'like', '5%')
                        ->orWhere('code', 'like', '9%');
                })
                ->where('type', '2')
                ->orderBy('code')
                ->get();
            return view('frontend.sales.invoice.edit_invoice', compact('codes', 'invoice', 'invoices', 'customers', 'client', 'profession'));
        } else {
            toast('No data found', 'error');
            return redirect()->route('invoice.manage');
        }
    }
    public function update(DedotrQuoteRequest $request, $invoice, Client $client, Profession $profession)
    {
        // return $request;
        $data = $request->validated();

        $data['tran_date'] = $tran_date = makeBackendCompatibleDate($request->start_date);
        if (periodLock($client->id, $tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        $accumData = Dedotr::where('client_id', $request->client_id)
            ->where('profession_id', $profession->id)
            ->where('customer_card_id', $request->customer_card_id)
            ->where('tran_id', $request->tran_id)
            ->get();

        if ($accumData->count() != Dedotr::where('tran_id', $request->tran_id)->count()) {
            Alert::error('There is a big problem. Please contact the administrator.');
            return back();
        }
        DB::beginTransaction();
        foreach ($request->job_title as $i => $jobTitle) {
            $dedotr = Dedotr::where('id', $request->inv_id[$i])->first();
            $rprice = $gst_total = $request->price[$i];
            $gst    = $trate     = $disc_amount = 0;

            if ($request->disc_rate[$i] != '') {
                $disc_amount = $rprice * ($request->disc_rate[$i] / 100);
                $rprice      = $gst_total = ($rprice - $disc_amount);
            }
            if ($request->freight_charge[$i] != '') {
                $rprice = $gst_total = ($request->freight_charge[$i]) + ($rprice);
            }
            if ($request->is_tax[$i] == 'yes') {
                $rprice = ($rprice) + ($rprice * 0.1);
                $gst    = $gst_total * 0.1;
                $trate  = 10.00;
            } else {
                $trate = 0.00;
            }
            // return $rprice;

            $data['job_title']      = $jobTitle;
            $data['job_des']        = $request->job_des[$i];
            $data['amount']         = $rprice;
            $data['price']          = $request->price[$i];
            $data['disc_rate']      = $request->disc_rate[$i];
            $data['disc_amount']    = $disc_amount;
            $data['freight_charge'] = $request->freight_charge[$i];
            $data['chart_id']       = $request->chart_id[$i];
            $data['is_tax']         = $request->is_tax[$i];
            $data['tax_rate']       = $request->tax_rate[$i];

            // return $data;
            if ($dedotr) {
                $data['accum_amount']         = $dedotr->accum_amount;
                $data['accum_payment_amount'] = $dedotr->accum_payment_amount;
                $dedotr->update($data);
            } else {
                $data['accum_amount']         = $rprice + $accumData->sum('amount');
                $data['accum_payment_amount'] = $accumData->max('accum_payment_amount');
                Dedotr::create($data);
            }
        }

        // GST TABKLE CALCULATION
        $dedotrs = Dedotr::where('client_id', $request->client_id)
            ->where('profession_id', $profession->id)
            ->where('inv_no', $invoice)
            ->where('tran_id', $request->tran_id)
            ->where('customer_card_id', $request->customer_card_id)
            ->orderBy('chart_id')
            ->get();
        foreach ($dedotrs->groupBy('chart_id') as $dedotr) {
            $gst = [
                'gst_cash_amount' => 0,
                'client_id'       => $dedotr->first()->client_id,
                'profession_id'   => $dedotr->first()->profession_id,
                'chart_code'      => $dedotr->first()->chart_id,
                'source'          => 'INV',
                'trn_id'          => $dedotr->first()->tran_id,
                'trn_date'        => $dedotr->first()->tran_date,
            ];

            $amount         = $dedotr->sum('amount');
            $price          = $dedotr->sum('price');
            $disc_rate      = $dedotr->sum('disc_rate') / $dedotr->count();
            $freight_charge = $dedotr->sum('freight_charge');
            if ($dedotr->first()->is_tax == 'yes') {
                $fPrice          = $price + ($price * 0.1);
                $pgst            = $price * 0.1;
                $fDisc_rate      = $price * ($disc_rate / 100) + (($price * ($disc_rate / 100)) * 0.1);
                $dgst            = ($price * ($disc_rate / 100)) * 0.1;
                $fFreight_charge = $freight_charge + ($freight_charge * 0.1);
                $fgst            = $freight_charge * 0.1;
            } else {
                $fPrice          = $price;
                $pgst            = 0;
                $fDisc_rate      = $price * ($disc_rate / 100);
                $dgst            = 0;
                $fFreight_charge = $freight_charge;
                $fgst            = 0;
            }

            $gst['gross_amount']       = $fPrice;
            $gst['gst_accrued_amount'] = $pgst;
            $gst['net_amount']         = $fPrice - $pgst;

            $checksGst = Gsttbl::where('client_id', $request->client_id)
                ->where('profession_id', $profession->id)
                ->where('trn_id', $dedotr->first()->tran_id)
                ->where('source', 'INV')->get();

            $checkGst = $checksGst->where('chart_code', $dedotr->first()->chart_id)->first();
            if ($checkGst) {
                $checkGst->update($gst);
            } else {
                Gsttbl::create($gst);
            }
            //Freight Charge [If Freight Charge Inserted]
            if ($dedotr->first()->freight_charge != '') {
                $gst['gross_amount']       = $fFreight_charge;
                $gst['gst_accrued_amount'] = $fgst;
                $gst['net_amount']         = $fFreight_charge - $fgst;
                if ($dedotr->first()->is_tax == 'yes') {
                    $gst['chart_code']         = 191295;
                    $checkfr              = $checksGst->where('chart_code', 191295)->first();
                    $gst['gross_amount']       = $fFreight_charge;
                    $gst['gst_accrued_amount'] = $fgst;
                    $gst['net_amount']         = $fFreight_charge - $fgst;
                    if ($checkfr != '') {
                        $checkfr->update($gst);
                    } else {
                        Gsttbl::create($gst);
                    }
                } else {
                    $gst['chart_code']         = 191296;
                    $checkfr              = $checksGst->where('chart_code', 191296)->first();
                    $gst['gross_amount']       = $fFreight_charge;
                    $gst['gst_accrued_amount'] = $fgst;
                    $gst['net_amount']         = $fFreight_charge - $fgst;
                    if ($checkfr != '') {
                        $checkfr->update($gst);
                    } else {
                        Gsttbl::create($gst);
                    }
                }
            }

            //Discount of Sales/Services [If discount data inserted]
            if ($dedotr->first()->disc_rate != '') {
                $gst['gross_amount']       = -$fDisc_rate;
                $gst['gst_accrued_amount'] = -$dgst;
                $gst['net_amount']         = -$fDisc_rate + $dgst;
                if ($dedotr->first()->is_tax == 'yes') {
                    $gst['chart_code']         = 191998;
                    $checkdis             = $checksGst->where('chart_code', 191998)->first();
                    $gst['gross_amount']       = -$fDisc_rate;
                    $gst['gst_accrued_amount'] = -$dgst;
                    $gst['net_amount']         = -$fDisc_rate + $dgst;
                    if ($checkdis != '') {
                        $checkdis->update($gst);
                    } else {
                        Gsttbl::create($gst);
                    }
                } else {
                    $gst['chart_code']         = 191999;
                    $checkdis             = $checksGst->where('chart_code', 191999)->first();
                    $gst['gross_amount']       = -$fDisc_rate;
                    $gst['gst_accrued_amount'] = -$dgst;
                    $gst['net_amount']         = -$fDisc_rate + $dgst;
                    if ($checkdis != '') {
                        $checkdis->update($gst);
                    } else {
                        Gsttbl::create($gst);
                    }
                }
            }
        }

        // Ledger DATA
        $ledger['date']           = $tran_date;
        $ledger['narration']      = $dedotr->first()->customer->name;
        $ledger['source']         = 'INV';
        $ledger['client_id']      = $cid              = $client->id;
        $ledger['profession_id']  = $pid              = $profession->id;
        $ledger['transaction_id'] = $tran_id          = $dedotrs->first()->tran_id;
        $ledger['balance_type']   = 2;
        $ledger['debit']          = $ledger['credit'] = 0;

        $gstData = Gsttbl::where('client_id', $request->client_id)
            ->where('profession_id', $profession->id)
            ->where('trn_id', $dedotrs->first()->tran_id)
            ->where('trn_date', $tran_date->format('Y-m-d'))
            ->where('source', 'INV')
            ->orderBy('chart_code')
            ->get();

        $codes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('code')
            ->get();
        $genLedger = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('transaction_id', $dedotrs->first()->tran_id)
            ->where('source', 'INV')
            ->get();

        $genLoopData = $genLedger;
        $genTrade    = $genLedger;

        // Account code fron INV
        foreach ($gstData as $gd) {
            $code                     = $codes->where('code', $gd->chart_code)->first();
            $ledger['chart_id']               = $code->code;
            $ledger['client_account_code_id'] = $code->id;
            $ledger['balance']                = abs($gd->net_amount);
            $ledger['gst']                    = abs($gd->gst_accrued_amount);
            if ($code->code == 191998 || $code->code == 191999) {
                $ledger['balance_type'] = 1;
                $ledger['debit']        = abs($gd->gross_amount);
                $ledger['credit']       = 0;
            } else {
                $ledger['balance_type'] = 2;
                $ledger['debit']        = 0;
                $ledger['credit']       = abs($gd->gross_amount);
            }
            $genLed = $genLoopData->where('chart_id', $gd->chart_code)->first();

            if ($genLed != '') {
                $genLed->update($ledger);
            } else {
                GeneralLedger::create($ledger);
            }
        }
        // Trade Debotrs code
        $trade                    = $codes->where('code', 552100)->first();
        $ledger['balance_type']           = 1;
        $ledger['chart_id']               = $trade->code;
        $ledger['client_account_code_id'] = $trade->id;
        $ledger['balance']                =
            $ledger['debit']                  = $dedotrs->sum('amount')  - $dedotrs->first()->payment_amount;
        $ledger['credit']                 = $ledger['gst'] = 0;
        $genCredit                = $genTrade->where('chart_id', 552100)->first();
        if ($genCredit != '') {
            $genCredit->update($ledger);
        } else {
            GeneralLedger::create($ledger);
        }
        // Gst Clear code
        $gstpay                   = $codes->where('code', 912100)->first();
        $ledger['balance_type']           = 2;
        $ledger['chart_id']               = $gstpay->code;
        $ledger['client_account_code_id'] = $gstpay->id;
        $ledger['balance']                = $ledger['credit'] = $gstData->sum('gst_accrued_amount');
        $ledger['debit']                  = $ledger['gst']    = 0;
        $genClear                 = $genLedger->where('chart_id', 912100)->first();
        if ($genClear != '') {
            $genClear->update($ledger);
        } else {
            GeneralLedger::create($ledger);
        }


        //RetailEarning Calculation
        RetainEarning::retain($cid, $pid, $tran_date, $ledger, ['INV', 'INV']);
        // Retain Earning For each Transaction
        RetainEarning::tranRetain($cid, $pid, $tran_id, $ledger, ['INV', 'INV']);
        //RetailEarning Calculation End....

        try {
            DB::commit();
            toast('Invoice Update Success', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            toast($e->getMessage(), 'error');
        }
        return redirect()->route('invoice.manage');
    }

    public function delete(Request $request)
    {
        try {
            $inv = Dedotr::find($request->id);
            if (periodLock($inv->client_id, $inv->tran_date)) {
                return response()->json('Your enter data period is locked, check administration', 500);
            }
            $invCount = Dedotr::whereClientId($inv->client_id)
                ->whereInvNo($inv->inv_no)
                ->whereCustomerCardId($inv->customer_card_id)
                ->count();
            if ($invCount == 1) {
                $message = ['message' => 'One item cannot be deleted. Please delete it from the invoice manage page', 'status' => '406'];
            } else {
                $inv->forceDelete();
                $message = ['message' => 'Invoice item delete success', 'status' => '200'];
            }
        } catch (\Exception $e) {
            $message = ['message' => 'Server Side Error!', 'status' => '500'];
            #$e->getMessage();
        }
        return response()->json($message);
    }
    public function destroy(Dedotr $invoice)
    {
        $invoice->load(['payments' => function ($q) use ($invoice) {
            $q->where('client_id', $invoice->client_id)
                ->where('profession_id', $invoice->profession_id);
        }]);
        if ($invoice->payments->count()) {
            Alert::error('Please delete payments first.');
            return redirect()->back();
        }
        if (in_array($invoice->tran_date->format('m'), range(1, 6))) {
            $start_year = $invoice->tran_date->format('Y') - 1 . '-07-01';
            $end_year   = $invoice->tran_date->format('Y') . '-06-30';
        } else {
            $start_year = $invoice->tran_date->format('Y') . '-07-01';
            $end_year   = $invoice->tran_date->format('Y') + 1 . '-06-30';
        }
        if (periodLock($invoice->client_id, $invoice->tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }

        $isRetain = GeneralLedger::where('client_id', $invoice->client_id)
            ->where('profession_id', $invoice->profession_id)
            ->where('date', '>=', $start_year)
            ->where('date', '<=', $end_year)
            ->where('chart_id', 999999)
            ->where('source', 'INV')
            ->first();
        if ($isRetain) {
            $pl = GeneralLedger::where('client_id', $invoice->client_id)
                ->where('profession_id', $invoice->profession_id)
                ->where('transaction_id', $invoice->tran_id)
                ->where('chart_id', 999998)->first();
            $rt['balance'] = $isRetain->balance - $pl->balance;

            if ($isRetain->credit != '') {
                $rt['credit'] = abs($rt['balance']);
                $rt['debit']  = 0;
            } else {
                $rt['debit']  = abs($rt['balance']);
                $rt['credit'] = 0;
            }
            $isRetain->update($rt);
        }

        // return $rt;
        GeneralLedger::where('client_id', $invoice->client_id)
            ->where('profession_id', $invoice->profession_id)
            ->where('transaction_id', $invoice->tran_id)
            ->where('chart_id', '!=', 999999)->delete();

        Gsttbl::where('client_id', $invoice->client_id)
            ->where('profession_id', $invoice->profession_id)
            ->where('trn_id', $invoice->tran_id)->delete();
        Dedotr::where('client_id', $invoice->client_id)
            ->where('profession_id', $invoice->profession_id)
            ->where('tran_id', $invoice->tran_id)->delete();

        try {
            toast('Invoice Delete success', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        // return redirect()->back();
        return redirect()->route('invoice.manage');
    }
}
