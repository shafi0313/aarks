<?php

namespace App\Http\Controllers\Frontend\Sales\Items;

use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Actions\RetainEarning;
use App\Models\Frontend\Dedotr;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Frontend\CustomerCard;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\DedotrQuoteRequest;
use App\Models\Frontend\DedotrQuoteOrder;
use App\Models\Frontend\InventoryCategory;
use App\Models\Frontend\InventoryRegister;
use App\Models\Frontend\DedotrPaymentReceive;

class DedotrInvoiceItemController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.sales.invoice_item.select_activity', compact('client'));
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
        $categories = InventoryCategory::with(['items' => function ($q) {
            $q->where('type', '!=', 1);
        }, 'items.code'])->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('parent_id', '!=', 0)
            ->get();

        $customers = CustomerCard::where('client_id', $client->id)->where('profession_id', $profession->id)->where('type', 1)->orderBy('name')->get();
        // $quotes    = DedotrQuoteOrder::where('client_id', $client->id)->where('source', 'invoice')->get();
        $codes     = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', 'like', '1%')
            ->where('type', '2')
            ->orderBy('code')
            ->get();

        $liquid_codes = ClientAccountCode::where('additional_category_id', aarks('liquid_asset_id'))
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('code', 'asc')
            ->get();

        return view('frontend.sales.invoice_item.service_invoice', compact('client', 'customers', 'codes', 'profession', 'liquid_codes', 'categories'));
    }

    public function customerList()
    {
        $client    = client();
        $quotes    = Dedotr::with('customer')->where('client_id', $client->id)->get()->groupBy('customer_card_id');
        return view('frontend.sales.invoice_item.customer_list', compact('client', 'quotes'));
    }
    public function manage()
    {
        $client    = client();

        $invoices = Dedotr::filter()->selectRaw('id, tran_date,tran_id,inv_no,amount,customer_card_id, sum(amount) as totalAmt')->with(['payments' => fn ($q) => $q->select('id', 'dedotr_inv', 'payment_amount')->where('client_id', $client->id), 'customer' => fn ($q) => $q->select('id', 'name')])->where('client_id', $client->id)
            ->where('chart_id', 'not like', '551%')
            ->where('item_name', '!=', '')
            ->groupBy(['tran_id'])
            // ->groupBy(['customer_card_id','inv_no'])
            ->get();

        // $invoices    = Dedotr::with(['payments' => function ($q) use ($client) {
        //     return $q->where('client_id', $client->id);
        // }])->where('client_id', $client->id)
        //     ->where('chart_id', 'not like', '551%')
        //     ->where('item_name', '!=', '')
        //     ->get();
        return view('frontend.sales.invoice_item.manage', compact('client', 'invoices'));
    }
    public function store(DedotrQuoteRequest $request)
    {
        // return $request;
        $data         = $request->validated();
        $client = Client::find($request->client_id);
        $data['tran_date'] = $tran_date = makeBackendCompatibleDate($request->start_date);
        if (periodLock($request->client_id, $tran_date)) {
            return response()->json('Your enter data period is locked, check administration', 500);
        }
        $period = Period::where('client_id', $client->id)
            ->where('profession_id', $request->profession_id)
            // ->where('start_date', '<=', $tran_date->format('Y-m-d'))
            ->where('end_date', '>=', $tran_date->format('Y-m-d'))
            ->first();

        DB::beginTransaction();
        if ($request->bank_account != '' && $request->payment_amount > 0) {
            //Payment Amount
            $data['payment_amount']      = abs($request->payment_amount);
            $data['accum_payment_amount'] = abs($request->payment_amount) + DedotrPaymentReceive::where('client_id', $client->id)->where('profession_id', $request->profession_id)->where('customer_card_id', $request->customer_card_id)->max('accum_payment_amount');
        }
        if ($period != '') {
            // $tran_id = $client->id.$request->profession_id.$period->id.$request->customer_card_id.$tran_date->format('dmy').rand(11, 99);
            $inv_no = Dedotr::whereClientId($client->id)->whereProfessionId($request->profession_id)->max('inv_no') + 1;
            $tran_id = transaction_id('INV');

            // Transaction Id duplicity check
            if(Dedotr::whereTran_id($tran_id)->count() > 0 || Dedotr::whereClientId($request->client_id)->whereInv_no($inv_no)->count() > 0){
                $message = ['status' => 406, 'message' => 'Something went wrong. Please try again.'];
                return response()->json($message);
            }

            foreach ($request->chart_id as $i => $chart_id) {
                $data['chart_id']       = $chart_id;
                $data['tran_id']        = $tran_id;
                $data['disc_rate']      = $request->disc_rate[$i];
                $data['disc_amount']    = $request->disc_amount[$i];
                $data['freight_charge'] = $request->freight_charge[$i];
                $data['is_tax']         = $request->is_tax[$i];
                $data['tax_rate']       = $request->tax_rate[$i];
                $data['ex_rate']        = $request->rate[$i];
                $data['alige']          = $request->alige[$i];
                $data['item_no']        = $request->item_id[$i];
                $data['item_name']      = $request->item_name[$i];
                $data['item_quantity']  = $request->quantity[$i];
                $data['price']          = $request->amount[$i];
                $data['amount']         = $request->totalamount[$i];
                $data['accum_amount']   = $request->totalamount[$i] + Dedotr::where('client_id', $client->id)->where('profession_id', $request->profession_id)->where('customer_card_id', $request->customer_card_id)->sum('amount');

                $dedo = Dedotr::create($data);

                $regData['client_id']         = $dedo->client_id;
                $regData['profession_id']     = $dedo->profession_id;
                $regData['inventory_item_id'] = $request->item_id[$i];
                $regData['source']            = 'sales';
                $regData['item_name']         = $request->item_reg_name[$i];
                $regData['date']              = $tran_date;
                $regData['sales_qty']         = $request->quantity[$i];
                $regData['sales_rate']        = $request->rate[$i];
                InventoryRegister::create($regData);
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
                ->where('profession_id', $request->profession_id)
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
                $gst['gross_cash_amount'] =  $gst['gst_cash_amount'] = 0;
                $gst['chart_code']   = $dedotr->first()->chart_id;
                $amount         = $dedotr->sum('amount');
                $price          = $dedotr->sum('price');
                $disc_rate      = $dedotr->sum('disc_rate') / $dedotr->count();
                $freight_charge = $dedotr->sum('freight_charge');
                $gst['source']  = 'INV';
                if ($dedotr->first()->is_tax == 'yes') {
                    $fPrice = $price + ($price * 0.1);
                    $pgst = $price * 0.1;
                    $fDisc_rate = $price * ($disc_rate / 100) + (($price * ($disc_rate / 100)) * 0.1);
                    $dgst = ($price * ($disc_rate / 100)) * 0.1;
                    $fFreight_charge = $freight_charge + ($freight_charge * 0.1);
                    $fgst = $freight_charge * 0.1;
                } else {
                    $fPrice          = $price;
                    $pgst            = 0;
                    $fDisc_rate      = $price * ($disc_rate / 100);
                    $dgst            = 0;
                    $fFreight_charge = $freight_charge;
                    $fgst            = 0;
                }
                $gst['gross_amount'] = $fPrice;
                $gst['gst_accrued_amount'] = $pgst;
                $gst['net_amount'] = $fPrice - $pgst;

                $checksGst = Gsttbl::where('client_id', $client->id)
                    ->where('profession_id', $request->profession_id)
                    ->where('trn_id', $tran_id)->where('source', 'INV')->get();

                $checkGst = $checksGst->where('chart_code', $dedotr->first()->chart_id)->first();
                if ($checkGst != '') {
                    $checkGst->update($gst);
                } else {
                    Gsttbl::create($gst);
                }
                if ($dedotr->first()->freight_charge != '') {
                    $gst['gross_amount'] = $fFreight_charge;
                    $gst['gst_accrued_amount'] = $fgst;
                    $gst['net_amount'] = $fFreight_charge - $fgst;
                    if ($dedotr->first()->is_tax == 'yes') {
                        $gst['chart_code'] = 191295;
                        $checkfr = $checksGst->where('chart_code', 191295)->first();
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
                        $checkfr = $checksGst->where('chart_code', 191296)->first();
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

                if ($dedotr->first()->disc_rate != '') {
                    $gst['gross_amount']       = -$fDisc_rate;
                    $gst['gst_accrued_amount'] = -$dgst;
                    $gst['net_amount']         = -$fDisc_rate + $dgst;
                    if ($dedotr->first()->is_tax == 'yes') {
                        $gst['chart_code'] = 191998;
                        $checkdis = $checksGst->where('chart_code', 191998)->first();
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
                        $checkdis = $checksGst->where('chart_code', 191999)->first();
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
            $ledger['narration']      = "Customer INV";
            $ledger['source']         = 'INV';
            $ledger['client_id']      = $cid = $client->id;
            $ledger['profession_id']  = $pid = $request->profession_id;
            $ledger['transaction_id'] = $tran_id;
            $ledger['balance_type']   = 2;
            $ledger['debit']          = $ledger['credit'] = 0;


            $gstData = Gsttbl::where('client_id', $client->id)
                ->where('profession_id', $request->profession_id)
                ->where('trn_id', $tran_id)
                ->where('trn_date', $tran_date->format('Y-m-d'))
                ->orderBy('chart_code')
                ->get();

            $codes = ClientAccountCode::where('client_id', $client->id)
                ->where('profession_id', $request->profession_id)->get();
            // Account code fron INV
            foreach ($gstData->where('source', 'INV') as $gd) {
                $code = $codes->where('code', $gd->chart_code)->first();
                $ledger['chart_id']               = $code->code;
                $ledger['client_account_code_id'] = $code->id;
                $ledger['balance']                = abs($gd->net_amount);
                $ledger['gst']                    = abs($gd->gst_accrued_amount);
                if ($code->code == 191998 || $code->code == 191999) {
                    $ledger['debit']  = abs($gd->gross_amount);
                    $ledger['credit'] = 0;
                    $ledger['balance_type']   = 1;
                } else {
                    $ledger['credit'] = abs($gd->gross_amount);
                    $ledger['debit']  = 0;
                }
                GeneralLedger::create($ledger);
            }
            // Trade Debotor code
            $trade = $codes->where('code', 552100)->first();
            $ledger['balance_type']           = 1;
            $ledger['chart_id']               = $trade->code;
            $ledger['client_account_code_id'] = $trade->id;
            $ledger['balance']                = $ledger['debit'] = $request->total_amount - $request->payment_amount;
            $ledger['credit']                 = $ledger['gst']   = 0;
            GeneralLedger::create($ledger);
            // Gst Payable code
            $gstpay = $codes->where('code', 912100)->first();
            $ledger['balance_type']           = 2;
            $ledger['chart_id']               = $gstpay->code;
            $ledger['client_account_code_id'] = $gstpay->id;
            $ledger['balance']                = $ledger['credit'] = $request->gst_amt_subtotal;
            $ledger['debit']                 = $ledger['gst']   = 0;
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
            //RetailEarning Calculation End....
            
            try {
                DB::commit();
                $toast = 'Invoice Create successfully';
                $message = ['status' => 200, 'message' => $toast, 'inv_no' => Dedotr::whereClientId($cid)->whereProfessionId($pid)->max('inv_no') + 1];
            } catch (\Exception $e) {
                DB::rollBack();
                $toast = $e->getMessage();
                $message = ['status' => 500, 'message' => $toast];
            }
        } else {
            $toast = 'Please check your accounting period from the accounts>add/edit period';
            $message = ['status' => 500, 'message' => $toast];
        }
        // Preview & Save
        if (!$request->ajax() && $period != '') {
            return redirect()->route('inv.report', ['item', $request->inv_no, $request->client_id, $request->customer_card_id]);
        }else{
            $toast = 'Please check your accounting period from the accounts>add/edit period';
            Alert::error($toast);
            return back();
        }
        return response()->json($message);
    }
    public function edit(Request $request, $inv_no, Client $client, $customer_card_id)
    {
        $invoices    = Dedotr::with(['client', 'customer', 'item'])
            ->where('client_id', $client->id)
            ->where('customer_card_id', $customer_card_id)
            ->where('item_name', '!=', '')
            ->where('inv_no', $inv_no)->get();
        $invoice = $invoices->first();
        if (periodLock($client->id, $invoice->tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        if ($request->ajax()) {
            return response()->json(['invoices' => $invoices, 'status' => 200]);
        }
        if ($invoices->count() > 0) {
            $codes   = ClientAccountCode::where('client_id', $invoice->client_id)
                ->where('profession_id', $invoice->profession_id)
                ->where('code', 'like', '1%')
                ->where('type', '2')
                ->orderBy('code')
                ->get();
            $client     = Client::find($invoice->client_id);
            $customers  = CustomerCard::where('client_id', $client->id)
                ->where('profession_id', $invoice->profession_id)
                ->where('type', 1)->get();
            $categories = InventoryCategory::with(['items' => function ($q) {
                $q->where('type', '!=', 1);
            }, 'items.code'])->where('client_id', $client->id)
                ->where('profession_id', $invoice->profession_id)
                ->where('parent_id', '!=', 0)
                ->get();

            return view('frontend.sales.invoice_item.edit_invoice', compact('invoice', 'invoices', 'customers', 'client', 'categories', 'codes', 'customer_card_id'));
        } else {
            toast('No Data found', 'error');
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
        $accumData = Dedotr::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('customer_card_id', $request->customer_card_id)
            ->where('tran_id', $request->tran_id)
            ->get();

        DB::beginTransaction();
        foreach ($request->item_name as $i => $item_name) {
            $dedotr = Dedotr::where('id', $request->inv_id[$i])->first();
            $rprice = $request->amount[$i];

            if ($request->has('disc_rate') && $request->disc_rate != '') {
                $data['disc_amount'] = $rprice * ($request->disc_rate[$i] / 100);
                $data['amount'] = $price = $rprice - ($rprice * ($request->disc_rate[$i] / 100));
            }
            if ($request->has('freight_charge') && $request->freight_charge != '') {
                $data['amount'] = $price = $price + $request->freight_charge[$i];
            }
            if ($request->is_tax[$i] == 'yes') {
                $data['amount']  = $price + ($price * 0.1);
            } else {
                $data['amount'] = $price;
            }

            $data['item_name']      = $item_name;
            $data['alige']          = $request->alige[$i];
            $data['item_no']        = $request->item_id[$i];
            $data['item_quantity']  = $request->quantity[$i];
            $data['price']          = $rprice;
            $data['disc_rate']      = $request->disc_rate[$i];
            $data['freight_charge'] = $request->freight_charge[$i];
            $data['chart_id']       = $request->chart_id[$i];
            $data['is_tax']         = $request->is_tax[$i];
            $data['accum_amount']   = $accumData->sum('ammount') + $data['amount'];
            $data['ex_rate']        = $request->rate[$i];


            if (notEmpty($dedotr)) {
                $dedotr->update($data);
            } else {
                Dedotr::create($data);
            }

            // Inventory Register
            $regData['client_id']         = $client->id;
            $regData['profession_id']     = $profession->id;
            $regData['inventory_item_id'] = $request->item_id[$i];
            $regData['source']            = 'sales';
            $regData['item_name']         = Str::slug($request->item_reg_name[$i]);
            $regData['date']              = $tran_date;
            $regData['sales_qty']         = $request->quantity[$i];
            $regData['sales_rate']        = $request->rate[$i];

            $chkInvReg = InventoryRegister::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('item_name', Str::slug($request->item_reg_name[$i]))
                ->where('inventory_item_id', $request->item_id[$i])
                ->latest()->first();

            if (notEmpty($chkInvReg)) {
                $chkInvReg->update($regData);
            } else {
                InventoryRegister::create($regData);
            }
        }

        $dedotrs = Dedotr::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('inv_no', $invoice)
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
                $fDisc_rate      = $price * ($disc_rate / 100);
                $fFreight_charge = $freight_charge;
                $pgst            = $dgst = $fgst = 0;
            }
            $gst['gross_amount']       = $fPrice;
            $gst['gst_accrued_amount'] = $pgst;
            $gst['net_amount']         = $fPrice - $pgst;

            $checksGst = Gsttbl::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('trn_id', $dedotr->first()->tran_id)
                ->where('source', 'INV')->get();

            $checkGst = $checksGst->where('chart_code', $dedotr->first()->chart_id)->first();
            if ($checkGst) {
                $checkGst->update($gst);
            } else {
                Gsttbl::create($gst);
            }
            if ($dedotr->first()->freight_charge != '') {
                $gst['gross_amount']       = $fFreight_charge;
                $gst['gst_accrued_amount'] = $fgst;
                $gst['net_amount']         = $fFreight_charge - $fgst;
                if ($dedotr->first()->is_tax == 'yes') {
                    $gst['chart_code'] = 191295;
                    $checkfr = $checksGst->where('chart_code', 191295)->first();
                    $gst['gross_amount']       = $fFreight_charge;
                    $gst['gst_accrued_amount'] = $fgst;
                    $gst['net_amount']         = $fFreight_charge - $fgst;
                    if (notEmpty($checkfr)) {
                        $checkfr->update($gst);
                    } else {
                        Gsttbl::create($gst);
                    }
                } else {
                    $gst['chart_code'] = 191296;
                    $checkfr = $checksGst->where('chart_code', 191296)->first();
                    $gst['gross_amount']       = $fFreight_charge;
                    $gst['gst_accrued_amount'] = $fgst;
                    $gst['net_amount']         = $fFreight_charge - $fgst;
                    if (notEmpty($checkfr)) {
                        $checkfr->update($gst);
                    } else {
                        Gsttbl::create($gst);
                    }
                }
            }

            if ($dedotr->first()->disc_rate != '') {
                $gst['gross_amount']       = -$fDisc_rate;
                $gst['gst_accrued_amount'] = -$dgst;
                $gst['net_amount']         = -$fDisc_rate + $dgst;
                if ($dedotr->first()->is_tax == 'yes') {
                    $gst['chart_code'] = 191998;
                    $checkdis = $checksGst->where('chart_code', 191998)->first();
                    $gst['gross_amount']       = -$fDisc_rate;
                    $gst['gst_accrued_amount'] = -$dgst;
                    $gst['net_amount']         = -$fDisc_rate + $dgst;
                    if (notEmpty($checkdis)) {
                        $checkdis->update($gst);
                    } else {
                        Gsttbl::create($gst);
                    }
                } else {
                    $gst['chart_code'] = 191999;
                    $checkdis = $checksGst->where('chart_code', 191999)->first();
                    $gst['gross_amount']       = -$fDisc_rate;
                    $gst['gst_accrued_amount'] = -$dgst;
                    $gst['net_amount']         = -$fDisc_rate + $dgst;
                    if (notEmpty($checkdis)) {
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
        $ledger['client_id']      = $cid     = $client->id;
        $ledger['profession_id']  = $pid     = $profession->id;
        $ledger['transaction_id'] = $tran_id = $dedotrs->first()->tran_id;
        $ledger['balance_type']   = 2;
        $ledger['debit']          = $ledger['credit'] = 0;

        $gstData = Gsttbl::where('client_id', $client->id)
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
            $code = $codes->where('code', $gd->chart_code)->first();
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

            if (notEmpty($genLed)) {
                $genLed->update($ledger);
            } else {
                GeneralLedger::create($ledger);
            }
        }
        // Trade Debotrs code
        $trade                            = $codes->where('code', 552100)->first();
        $ledger['balance_type']           = 1;
        $ledger['chart_id']               = $trade->code;
        $ledger['client_account_code_id'] = $trade->id;
        $ledger['balance']                =
            $ledger['debit']                  = $dedotrs->sum('amount')  - $dedotrs->first()->payment_amount;
        $ledger['credit']                 = $ledger['gst'] = 0;
        $genCredit                        = $genTrade->where('chart_id', 552100)->first();
        if (notEmpty($genCredit)) {
            $genCredit->update($ledger);
        } else {
            GeneralLedger::create($ledger);
        }
        // Gst Clear code
        $gstpay = $codes->where('code', 912100)->first();
        $ledger['balance_type']           = 2;
        $ledger['chart_id']               = $gstpay->code;
        $ledger['client_account_code_id'] = $gstpay->id;
        $ledger['balance']                =   $ledger['credit'] =  $gstData->sum('gst_accrued_amount');
        $ledger['debit']               = $ledger['gst']   = 0;
        $genClear = $genLedger->where('chart_id', 912100)->first();
        if (notEmpty($genClear)) {
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
            toast('Invoice Updated Success', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            toast($e->getMessage(), 'error');
        }
        return redirect()->route('invoice_item.manage');
    }

    public function delete(Request $request)
    {
        try {
            $inv = Dedotr::find($request->id);
            if (periodLock($inv->client_id, $inv->tran_date)) {
                return response()->json('Your enter data period is locked, check administration', 500);
            }
            $inv->delete();
            $message = ['message' => 'dedotr deleted success', 'status' => '200'];
        } catch (\Exception $e) {
            $message = ['message' => 'Server Side Error!', 'status' => '500'];
            #$e->getMessage();
        }
        return response()->json($message);
    }
    public function destroy(Dedotr $invoice_item)
    {
        $invoice_item->load(['payments' => function ($q) use ($invoice_item) {
            $q->where('client_id', $invoice_item->client_id)
                ->where('profession_id', $invoice_item->profession_id);
        }]);
        // return $invoice_item->payments;
        if ($invoice_item->payments->count()) {
            Alert::error('Please delete payments first.');
            return redirect()->back();
        }
        $ledgers = GeneralLedger::where('client_id', $invoice_item->client_id)
            ->where('profession_id', $invoice_item->profession_id)
            ->where('transaction_id', $invoice_item->tran_id)
            ->where('chart_id', '!=', 999999);

        if (in_array($invoice_item->tran_date->format('m'), range(1, 6))) {
            $start_date = $invoice_item->tran_date->format('Y') - 1 . '-07-01';
            $end_date   = $invoice_item->tran_date->format('Y') . '-06-30';
        } else {
            $start_date = $invoice_item->tran_date->format('Y') . '-07-01';
            $end_date   = $invoice_item->tran_date->format('Y') + 1 . '-06-30';
        }
        if ($ledgers->get()->first()->source == 'INV') {
            $source = 'INV';
        } else {
            $source = 'PIN';
        }
        if (periodLock($invoice_item->client_id, $invoice_item->tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }

        $inRetain   = GeneralLedger::where('client_id', $invoice_item->client_id)
            ->where('profession_id', $invoice_item->profession_id)
            ->where('transaction_id', $invoice_item->tran_id)
            ->where('chart_id', 'LIKE', '1%')
            ->where('source', $source)
            ->get();

        $retainData = $inRetain->where('balance_type', 2)->sum('balance') -
            $inRetain->where('balance_type', 1)->sum('balance');

        $retain = GeneralLedger::where('client_id', $invoice_item->client_id)
            ->where('profession_id', $invoice_item->profession_id)
            ->where('chart_id', 999999)
            ->where('source', $source)
            ->where('date', '>=', $start_date)
            ->where('date', '<=', $end_date)
            ->first();
        if ($retain) {
            $rt['balance'] = $retain->balance - $retainData;
            if ($retain->debit != '') {
                $rt['debit'] = abs($retain->debit - $retainData);
            } else {
                $rt['credit'] = abs($retain->credit - $retainData);
            }

            $retain->update($rt);
        }
        $ledgers->delete();
        Gsttbl::where('client_id', $invoice_item->client_id)
            ->where('profession_id', $invoice_item->profession_id)
            ->where('trn_id', $invoice_item->tran_id)->delete();

        Dedotr::where('client_id', $invoice_item->client_id)
            ->where('profession_id', $invoice_item->profession_id)
            ->where('tran_id', $invoice_item->tran_id)->delete();

        try {
            toast('Invoice Delete successfully', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        // return redirect()->back();
        return redirect()->route('invoice_item.manage');
    }
}
