<?php

namespace App\Http\Controllers\Frontend\Purchase\Items;

use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Actions\RetainEarning;
use App\Models\ClientAccountCode;
use App\Models\Frontend\Creditor;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Frontend\CustomerCard;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\DedotrQuoteRequest;
use App\Models\Frontend\InventoryCategory;
use App\Models\Frontend\InventoryRegister;
use App\Models\Frontend\CreditorPaymentReceive;

class CreditorItemController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.purchase.item.enter_item.select_activity', compact('client'));
    }
    public function quote(Profession $profession)
    {
        $client  = Client::find(client()->id);
        $payment  = $client->payment;
        $quation = Creditor::whereClientId($client->id)->whereBetween('tran_date', [$payment->started_at->format('Y-m-d'), $payment->expire_at->format('Y-m-d')])->count();
        if ($quation > $payment->bitll) {
            toast('Enter Bill limit reached.', 'error');
            return redirect()->back();
        }

        $categories = InventoryCategory::with(['items'=>function ($q) {
            $q->where('type', '!=', 2);
        },'items.code'])->where('client_id', $client->id)
        ->where('profession_id', $profession->id)
        ->where('parent_id', '!=', 0)
        ->get();
        $suppliers = CustomerCard::where('client_id', $client->id)->where('profession_id', $profession->id)->where('type', 2)->orderBy('name')->get();

        $codes     = ClientAccountCode::where('client_id', $client->id)
        ->where('profession_id', $profession->id)
        ->where('code', 'like', '2%')
        ->where('type', 1)
        ->orderBy('code')
        ->get();

        $liquid_codes = ClientAccountCode::where('additional_category_id', aarks('liquid_asset_id'))
        ->where('client_id', $client->id)
        ->where('profession_id', $profession->id)
        ->orderBy('code', 'asc')
        ->get();
        return view('frontend.purchase.item.enter_item.bill', compact('client', 'suppliers', 'codes', 'profession', 'liquid_codes', 'categories'));
    }

    public function customerList()
    {
        $client    = client();
        $quotes    = Creditor::with('customer')->where('client_id', $client->id)->get()->groupBy('customer_card_id');
        return view('frontend.purchase.item.enter_item.customer_list', compact('client', 'quotes'));
    }
    public function manage()
    {
        $client   = client();
        $services = Creditor::with(['payments' => function ($q) use ($client) {
            return $q->where('client_id', $client->id);
        }])->where('client_id', $client->id)
                    ->whereNotNull('item_name')
                    ->where('chart_id', 'not like', '551%')->get();
        return view('frontend.purchase.item.enter_item.manage', compact('client', 'services'));
    }
    public function store(DedotrQuoteRequest $request)
    {
        // return $request;
        $data   = $request->validated();
        $client = Client::find($request->client_id);

        $data['tran_date'] = $tran_date = makeBackendCompatibleDate($request->start_date);

        if (periodLock($request->client_id, $tran_date)) {
            return response()->json('Your enter data period is locked, check administration', 500);
        }
        $period = Period::where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)
                // ->where('start_date', '<=', $tran_date->format('Y-m-d'))
                ->where('end_date', '>=', $tran_date->format('Y-m-d'))
                ->first();

        DB::beginTransaction();
        if ($request->bank_account != '' && $request->payment_amount > 0) {
            //Payment Amount
            $data['payment_amount']       = abs($request->payment_amount);
            $data['accum_payment_amount'] = abs($request->payment_amount) + CreditorPaymentReceive::where('client_id', $client->id)->where('profession_id', $request->profession_id)->where('customer_card_id', $request->customer_card_id)->max('accum_payment_amount');
        }
        if ($period != '') {
            // $tran_id = $request->client_id.$request->profession_id.$period->id.$request->customer_card_id.$tran_date->format('dmy').rand(11, 99);
            $tran_id = transaction_id('PBN');

            foreach ($request->chart_id as $i => $chart_id) {
                $data['chart_id']             = $chart_id;
                $data['tran_id']              = $tran_id;
                $data['disc_rate']            = $request->disc_rate[$i];
                $data['disc_amount']          = $request->disc_amount[$i];
                $data['freight_charge']       = $request->freight_charge[$i];
                $data['is_tax']               = $request->is_tax[$i];
                $data['amount']               = $request->totalamount[$i];
                $data['accum_amount']         = $request->totalamount[$i] + Creditor::where('client_id', $client->id)->where('profession_id', $request->profession_id)->where('customer_card_id', $request->customer_card_id)->sum('amount');
                $data['ex_rate']              = $request->rate[$i];
                $data['item_no']              = $request->item_id[$i];
                $data['item_name']            = $request->item_name[$i];
                $data['item_quantity']        = $request->quantity[$i];
                $data['price']                = $request->amount[$i];

                if ($request->is_tax[$i] == 'yes') {
                    $data ['tax_rate']   = 10;
                } else {
                    $data ['tax_rate']   = 0;
                }
                $dedo = Creditor::create($data);

                $regData['client_id']         = $dedo->client_id;
                $regData['profession_id']     = $dedo->profession_id;
                $regData['inventory_item_id'] = $request->item_id[$i];
                $regData['source']            = 'purchase';
                $regData['item_name']         = $request->item_reg_name[$i];
                $regData['date']              = $tran_date;
                $regData['purchase_qty']      = $request->quantity[$i];
                $regData['purchase_rate']     = $request->rate[$i];
                InventoryRegister::create($regData);
            }

            if ($request->bank_account != '' && $request->payment_amount > 0) {
                //Payment Amount
                $payment                 = $data;
                $payment['chart_id']     = $request->bank_account;
                $payment['creditor_inv'] = $request->inv_no;
                $payment['source']       = $client->invoiceLayout->layout == 2?2:1;

                CreditorPaymentReceive::create($payment);
            }
            $creditors = Creditor::where('client_id', $client->id)
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
                'source'             => 'PBN',
                'chart_code'         => $request->chart_id[0],
                'gross_amount'       => $request->payment_amount,
                'gross_cash_amount'  => 0,
                'gst_accrued_amount' => 0,
                'gst_cash_amount'    => $request->payment_amount/11,
                'net_amount'         => ($request->payment_amount - ($request->payment_amount/11)),
            ];
            if ($request->bank_account != '' && $request->payment_amount != 0) {
                Gsttbl::create($gst);
            }
            foreach ($creditors as $creditor) {
                $gst['gross_cash_amount'] =  $gst['gst_cash_amount'] = 0;
                $gst['chart_code']   = $creditor->first()->chart_id;
                $amount         = $creditor->sum('amount');
                $price          = $creditor->sum('price');
                $disc_rate      = $creditor->sum('disc_rate')/$creditor->count();
                $freight_charge = $creditor->sum('freight_charge');
                $gst['source']  = 'PBP';
                if ($creditor->first()->is_tax == 'yes') {
                    $fPrice          = $price + ($price * 0.1);
                    $pgst            = $price * 0.1 ;
                    $fDisc_rate      = $price * ($disc_rate/100) + (($price * ($disc_rate/100)) * 0.1) ;
                    $dgst            = ($price * ($disc_rate/100)) * 0.1 ;
                    $fFreight_charge = $freight_charge + ($freight_charge * 0.1);
                    $fgst            = $freight_charge * 0.1 ;
                } else {
                    $fPrice          = $price;
                    $pgst            =0;
                    $fDisc_rate      = $price * ($disc_rate/100);
                    $dgst            =0;
                    $fFreight_charge = $freight_charge ;
                    $fgst            =0;
                }
                $gst['gross_amount']       = $fPrice;
                $gst['gst_accrued_amount'] = $pgst;
                $gst['net_amount']         = $fPrice - $pgst;

                $checksGst = Gsttbl::where('client_id', $client->id)
                ->where('profession_id', $request->profession_id)
                ->where('trn_id', $tran_id)->where('source', 'PBP')->get();

                $checkGst = $checksGst->where('chart_code', $creditor->first()->chart_id)->first();
                if ($checkGst != '') {
                    $checkGst->update($gst);
                } else {
                    Gsttbl::create($gst);
                }
                if ($creditor->first()->freight_charge != '') {
                    $gst['gross_amount']       = $fFreight_charge;
                    $gst['gst_accrued_amount'] = $fgst;
                    $gst['net_amount']         = $fFreight_charge - $fgst;
                    if ($creditor->first()->is_tax == 'yes') {
                        $gst['chart_code'] = 242100;
                        $checkfr = $checksGst->where('chart_code', 242100)->first();
                        if ($checkfr != '') {
                            $gst['gross_amount']       =$checkfr->gross_amount + $fFreight_charge;
                            $gst['gst_accrued_amount'] =$checkfr->gst_accrued_amount + $fgst;
                            $gst['net_amount']         =$checkfr->net_amount + $fFreight_charge - $fgst;
                            $checkfr->update($gst);
                        } else {
                            Gsttbl::create($gst);
                        }
                    } else {
                        $gst['chart_code'] = 242101;
                        $checkfr = $checksGst->where('chart_code', 242101)->first();
                        if ($checkfr != '') {
                            $gst['gross_amount']       =$checkfr->gross_amount + $fFreight_charge;
                            $gst['gst_accrued_amount'] =$checkfr->gst_accrued_amount + $fgst;
                            $gst['net_amount']         =$checkfr->net_amount + $fFreight_charge - $fgst;
                            $checkfr->update($gst);
                        } else {
                            Gsttbl::create($gst);
                        }
                    }
                }

                if ($creditor->first()->disc_rate != '') {
                    $gst['gross_amount']       = - $fDisc_rate;
                    $gst['gst_accrued_amount'] = - $dgst;
                    $gst['net_amount']         = - $fDisc_rate + $dgst;
                    if ($creditor->first()->is_tax == 'yes') {
                        $gst['chart_code'] = 228998;
                        $checkdis = $checksGst->where('chart_code', 228998)->first();
                        if ($checkdis != '') {
                            $gst['gross_amount']       = $checkdis->gross_amount - $fDisc_rate;
                            $gst['gst_accrued_amount'] = $checkdis->gst_accrued_amount - $dgst;
                            $gst['net_amount']         = $checkdis->net_amount - $fDisc_rate + $dgst;
                            $checkdis->update($gst);
                        } else {
                            Gsttbl::create($gst);
                        }
                    } else {
                        $gst['chart_code'] = 228999;
                        $checkdis = $checksGst->where('chart_code', 228999)->first();
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
            $ledger['narration']      = $creditor->first()->customer->name;
            $ledger['source']         = 'PBP';
            $ledger['client_id']      = $cid = $client->id;
            $ledger['profession_id']  = $pid = $request->profession_id;
            $ledger['transaction_id'] = $tran_id;
            $ledger['balance_type']   = 1;
            $ledger['debit']          = $ledger['credit'] = 0;


            $gstData = Gsttbl::where('client_id', $client->id)
                ->where('profession_id', $request->profession_id)
                ->where('trn_id', $tran_id)
                ->where('trn_date', $tran_date->format('Y-m-d'))
                ->orderBy('chart_code')
                ->get();

            $codes = ClientAccountCode::where('client_id', $client->id)
                    ->where('profession_id', $request->profession_id)
                    ->get();
            // Account code fron INV
            foreach ($gstData->where('source', 'PBP') as $gd) {
                $code = $codes->where('code', $gd->chart_code)->first();
                // if (!$code) {
                //     return $gd;
                // }
                $ledger['chart_id']               = $code->code;
                $ledger['client_account_code_id'] = $code->id;
                $ledger['balance']                = abs($gd->net_amount);
                $ledger['gst']                    = abs($gd->gst_accrued_amount);
                if ($code->code == 228998 || $code->code == 228999) {
                    $ledger['balance_type']   = 2;
                    $ledger['credit']  = abs($gd->gross_amount);
                    $ledger['debit'] = 0;
                } else {
                    $ledger['balance_type']   = 1;
                    $ledger['credit'] = 0;
                    $ledger['debit']  = abs($gd->gross_amount);
                }
                GeneralLedger::create($ledger);
            }
            // Trade Creditor code
            $trade = $codes->where('code', 911999)->first();
            $ledger['balance_type']           = 2;
            $ledger['chart_id']               = $trade->code;
            $ledger['client_account_code_id'] = $trade->id;
            $ledger['balance']                = $ledger['credit'] = $request->total_amount - $request->payment_amount;
            $ledger['debit']                 = $ledger['gst']   = 0;
            GeneralLedger::create($ledger);
            // Gst Clear code
            $gstpay = $codes->where('code', 912101)->first();
            $ledger['balance_type']           = 1;
            $ledger['chart_id']               = $gstpay->code;
            $ledger['client_account_code_id'] = $gstpay->id;
            $ledger['balance']                = $ledger['debit']  =  $request->gst_amt_subtotal;
            $ledger['credit']                 = $ledger['gst']   = 0;
            GeneralLedger::create($ledger);
            // payment received code or bank AC
            $bankAC = $gstData->where('source', 'PBN')->first();
            if (!empty($bankAC) && $request->payment_amount != '' && $request->bank_account != '') {
                $ledger['chart_id']               = $request->bank_account;
                $ledger['credit']                  = $ledger['balance'] = $bankAC->gross_amount;
                $ledger['gst']                    = 0;
                $ledger['balance_type']           = 2;
                $ledger['client_account_code_id'] = $codes->where('code', $request->bank_account)->first()->id;
                $ledger['debit']                 = 0;
                $ledger['source']                 = 'PBN';
                $ledger['narration']              = $creditor->first()->customer->name.' INVPayment';
                GeneralLedger::create($ledger);
            }

            //RetailEarning Calculation
            RetainEarning::retain($client->id, $request->profession_id, $tran_date, $ledger, ['PBP','PBP']);

            // Retain Earning For each Transection
            RetainEarning::tranRetain($client->id, $request->profession_id, $creditor->first()->tran_id, $ledger, ['PBP','PBP']);
            //RetailEarning Calculation End....

            try {
                DB::commit();
                $toast='Order Create successfully';
                $message = ['status'=>200,'message'=>$toast,'inv_no'=> Creditor::max('inv_no')+1];
            } catch (\Exception $e) {
                DB::rollBack();
                $toast=$e->getMessage();
                $message = ['status'=>500,'message'=>$toast];
            }
        } else {
            $toast = ' please check your accounting period from the Accouts>add/editperiod';
            $message = ['status'=>500,'message'=>$toast];
        }
        return response()->json($message);
    }
    public function edit(Request $request, $inv_no, Client $client)
    {
        $services    = Creditor::with(['client', 'customer', 'item'])
                    ->where('client_id', $client->id)
                    ->where('inv_no', $inv_no)
                    ->get();
        $service     = $services->first();
        if (periodLock($service->client_id, $service->tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        if ($request->ajax()) {
            return response()->json(['services'=>$services,'status'=>200]);
        }
        if ($services->count() > 0) {
            $codes   = ClientAccountCode::where('client_id', $service->client_id)
                    ->where('profession_id', $service->profession_id)
                    ->where('code', 'like', '2%')
                    ->where('type', '1')
                    ->orderBy('code')
                    ->get();
            $client     = Client::find($service->client_id);
            $suppliers  = CustomerCard::where('client_id', $client->id)
                        ->where('profession_id', $service->profession_id)
                        ->where('type', 2)->get();
            $categories = InventoryCategory::with(['items'=>function ($q) {
                $q->where('type', '!=', 2);
            },'items.code'])->where('client_id', $client->id)
            ->where('profession_id', $service->profession_id)
            ->where('parent_id', '!=', 0)
            ->get();

            return view('frontend.purchase.item.enter_item.edit_service', compact('codes', 'service', 'services', 'suppliers', 'client', 'categories'));
        } else {
            toast('No Data found', 'error');
            return redirect()->route('enter$enter_item.manage');
        }
    }
    public function update(DedotrQuoteRequest $request, $enter_item)
    {
        // return $request;
        $data = $request->validated();
        $data['start_date'] = $tran_date = makeBackendCompatibleDate($request->start_date);

        if (periodLock($request->client_id, $tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        $accumData = Creditor::where('client_id', $request->client_id)
                    ->where('profession_id', $request->profession_id)
                    ->where('customer_card_id', $request->customer_card_id)->get();

        $period = Period::where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)
                // ->where('start_date', '<=', $tran_date->format('Y-m-d'))
                ->where('end_date', '>=', $tran_date->format('Y-m-d'))
                ->first();
        DB::beginTransaction();
        foreach ($request->item_name as $i => $item_name) {
            $creditor = Creditor::where('id', $request->inv_id[$i])->first();
            $rprice = $request->amount[$i];


            if ($request->has('disc_rate') && $request->disc_rate != '') {
                $data['disc_amount'] = $rprice * ($request->disc_rate[$i]/100);
                $data['amount'] = $price = $rprice - ($rprice * ($request->disc_rate[$i]/100));
            }
            if ($request->has('freight_charge') && $request->freight_charge != '') {
                $data['amount'] = $price = $price + $request->freight_charge[$i];
            }
            if ($request->is_tax[$i] == 'yes') {
                $data['amount']  = $price + ($price * 0.1);
            } else {
                $data['amount'] = $price ;
            }

            $data['item_name']      = $item_name;
            $data['item_no']        = $request->item_id[$i];
            $data['item_quantity']  = $request->quantity[$i];
            $data['price']          = $rprice;
            $data['disc_rate']      = $request->disc_rate[$i];
            $data['freight_charge'] = $request->freight_charge[$i];
            $data['chart_id']       = $request->chart_id[$i];
            $data['is_tax']         = $request->is_tax[$i];
            $data['accum_amount']   = $accumData->sum('ammount') + $data['amount'];
            $data['ex_rate']        = $request->rate[$i];
            if (notEmpty($creditor)) {
                $creditor->update($data);
            } else {
                Creditor::create($data);
            }

            // Inventory Register
            $regData['client_id']         = $request->client_id;
            $regData['profession_id']     = $request->profession_id;
            $regData['inventory_item_id'] = $request->item_id[$i];
            $regData['source']            = 'purchase';
            $regData['item_name']         = Str::slug($request->item_reg_name[$i]);
            $regData['date']              = $tran_date;
            $regData['purchase_qty']      = $request->quantity[$i];
            $regData['purchase_rate']     = $request->rate[$i];
            $chkInvReg = InventoryRegister::where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)
                ->where('item_name', Str::slug($request->item_reg_name[$i]))
                ->where('inventory_item_id', $request->item_id[$i])
                ->latest()->first();
            if (notEmpty($chkInvReg)) {
                $chkInvReg->update($regData);
            } else {
                InventoryRegister::create($regData);
            }
        }
        $creditors = Creditor::where('client_id', $request->client_id)
                    ->where('profession_id', $request->profession_id)
                    ->where('inv_no', $enter_item)
                    ->where('customer_card_id', $request->customer_card_id)
                    ->orderBy('chart_id')
                    ->get();
        foreach ($creditors->groupBy('chart_id') as $creditor) {
            $gst['gst_cash_amount'] = 0;
            $gst['chart_code']      = $creditor->first()->chart_id;
            $amount         = $creditor->sum('amount');
            $price          = $creditor->sum('price');
            $disc_rate      = $creditor->sum('disc_rate')/$creditor->count();
            $freight_charge = $creditor->sum('freight_charge');
            $gst['source']  = 'PBP';
            if ($creditor->first()->is_tax == 'yes') {
                $fPrice          = $price + ($price * 0.1);
                $pgst            = $price * 0.1 ;
                $fDisc_rate      = $price * ($disc_rate/100) + (($price * ($disc_rate/100)) * 0.1) ;
                $dgst            = ($price * ($disc_rate/100)) * 0.1 ;
                $fFreight_charge = $freight_charge + ($freight_charge * 0.1);
                $fgst            = $freight_charge * 0.1 ;
            } else {
                $fPrice          = $price;
                $fDisc_rate      = $price * ($disc_rate/100);
                $fFreight_charge = $freight_charge ;
                $pgst            = $dgst = $fgst = 0;
            }
            $gst['gross_amount']       = $fPrice;
            $gst['gst_accrued_amount'] = $pgst;
            $gst['net_amount']         = $fPrice - $pgst;

            $checksGst = Gsttbl::where('client_id', $request->client_id)
                        ->where('profession_id', $request->profession_id)
                        ->where('trn_id', $creditor->first()->tran_id)
                        ->where('source', 'PBP')->get();

            $checkGst = $checksGst->where('chart_code', $creditor->first()->chart_id)->first();
            if ($checkGst) {
                $checkGst->update($gst);
            } else {
                Gsttbl::create($gst);
            }
            if ($creditor->first()->freight_charge != '') {
                $gst['gross_amount']       = $fFreight_charge;
                $gst['gst_accrued_amount'] = $fgst;
                $gst['net_amount']         = $fFreight_charge - $fgst;
                if ($creditor->first()->is_tax == 'yes') {
                    $gst['chart_code'] = 242100;
                    $checkfr = $checksGst->where('chart_code', 242100)->first();
                    $gst['gross_amount']       = $fFreight_charge;
                    $gst['gst_accrued_amount'] = $fgst;
                    $gst['net_amount']         = $fFreight_charge - $fgst;
                    if (notEmpty($checkfr)) {
                        $checkfr->update($gst);
                    } else {
                        Gsttbl::create($gst);
                    }
                } else {
                    $gst['chart_code'] = 242101;
                    $checkfr = $checksGst->where('chart_code', 242101)->first();
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

            if ($creditor->first()->disc_rate != '') {
                $gst['gross_amount']       = - $fDisc_rate;
                $gst['gst_accrued_amount'] = - $dgst;
                $gst['net_amount']         = - $fDisc_rate + $dgst;
                if ($creditor->first()->is_tax == 'yes') {
                    $gst['chart_code'] = 228998;
                    $checkdis = $checksGst->where('chart_code', 228998)->first();
                    $gst['gross_amount']       = - $fDisc_rate;
                    $gst['gst_accrued_amount'] = - $dgst;
                    $gst['net_amount']         = - $fDisc_rate + $dgst;
                    if (notEmpty($checkdis)) {
                        $checkdis->update($gst);
                    } else {
                        Gsttbl::create($gst);
                    }
                } else {
                    $gst['chart_code'] = 228999;
                    $checkdis = $checksGst->where('chart_code', 228999)->first();
                    $gst['gross_amount']       = - $fDisc_rate;
                    $gst['gst_accrued_amount'] = - $dgst;
                    $gst['net_amount']         = - $fDisc_rate + $dgst;
                    if (notEmpty($checkdis)) {
                        $checkdis->update($gst);
                    } else {
                        Gsttbl::create($gst);
                    }
                }
            }
        }
        // Ledger DATA
        $gstData = Gsttbl::where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)
                ->where('trn_id', $creditors->first()->tran_id)
                ->where('trn_date', $tran_date->format('Y-m-d'))
                ->orderBy('chart_code')
                ->get();

        $codes = ClientAccountCode::where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)
                ->orderBy('code')
                ->get();
        $genLedger = GeneralLedger::where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)
                ->where('transaction_id', $creditor->first()->tran_id)
                ->where('source', 'PBP')
                ->get();
        // Account code for PBN
        foreach ($gstData->where('source', 'PBP') as $gd) {
            $code = $codes->where('code', $gd->chart_code)->first();

            $ledger['date']                   = $tran_date;
            $ledger['narration']              = $creditor->first()->customer->name;
            $ledger['source']                 = 'PBP';
            $ledger['client_id']              = $request->client_id;
            $ledger['profession_id']          = $request->profession_id;
            $ledger['transaction_id']         = $gd->trn_id;
            $ledger['debit']                  = $ledger['credit'] = $ledger['balance'] = 0;
            $ledger['chart_id']               = $code->code;
            $ledger['client_account_code_id'] = $code->id;
            $ledger['balance']                = abs($gd->net_amount);
            $ledger['gst']                    = abs($gd->gst_accrued_amount);
            if ($code->code == 228998 || $code->code == 228999) {
                $ledger['balance_type']   = 2;
                $ledger['credit']  = abs($gd->gross_amount);
                $ledger['debit'] = 0;
            } else {
                $ledger['balance_type']   = 1;
                $ledger['credit'] = 0;
                $ledger['debit']  = abs($gd->gross_amount);
            }
            $genLed = $genLedger->where('chart_id', $gd->chart_code)->first();

            if (notEmpty($genLed)) {
                $genLed->update($ledger);
            } else {
                GeneralLedger::create($ledger);
            }
        }
        // Trade Creditor code
        $trade = $codes->where('code', 911999)->first();
        $ledger['balance_type']           = 2;
        $ledger['chart_id']               = $trade->code;
        $ledger['client_account_code_id'] = $trade->id;
        $ledger['balance']                =
        $ledger['credit']                 = $creditors->sum('amount') - $creditors->first()->payment_amount;
        $ledger['debit']                 = $ledger['gst']   = 0;
        $genCredit = $genLedger->where('chart_id', 911999)->first();
        if (notEmpty($genCredit)) {
            $genCredit->update($ledger);
        } else {
            GeneralLedger::create($ledger);
        }
        // Gst Clear code
        $gstpay = $codes->where('code', 912101)->first();
        $ledger['balance_type']           = 1;
        $ledger['chart_id']               = $gstpay->code;
        $ledger['client_account_code_id'] = $gstpay->id;
        $ledger['balance']                = $ledger['debit']  =  $gstData->sum('gst_accrued_amount');
        $ledger['credit']                = $ledger['gst']   = 0;
        $genClear = $genLedger->where('chart_id', 912101)->first();
        if (notEmpty($genClear)) {
            $genClear->update($ledger);
        } else {
            GeneralLedger::create($ledger);
        }


        //RetailEarning Calculation

        RetainEarning::retain($request->client_id, $request->profession_id, $tran_date, $ledger, ['PBP','PBP']);

        // Retain Earning For each Transection
        RetainEarning::tranRetain($request->client_id, $request->profession_id, $creditors->first()->tran_id, $ledger, ['PBP','PBP']);
        //RetailEarning Calculation End....

        try {
            DB::commit();
            toast('Bill Updated Success', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            toast('Server Side Error!', 'error');
            // return $e->getMessage();
        }
        return redirect()->route('enter_item.manage');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Frontend\DedotrQuoteOrder  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Creditor $enter_item)
    {
        $enter_item->load(['payments'=> function ($q) use ($enter_item) {
            $q->where('client_id', $enter_item->client_id)
            ->where('profession_id', $enter_item->profession_id);
        }]);
        if ($enter_item->payments->count()) {
            Alert::error('Please delete payments first.');
            return redirect()->back();
        }
        if (in_array($enter_item->tran_date->format('m'), range(1, 6))) {
            $start_year = $enter_item->tran_date->format('Y') - 1 . '-07-01';
            $end_year   = $enter_item->tran_date->format('Y') . '-06-30';
        } else {
            $start_year = $enter_item->tran_date->format('Y') . '-07-01';
            $end_year   = $enter_item->tran_date->format('Y')+1 . '-06-30';
        }

        if (periodLock($enter_item->client_id, $enter_item->tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        $isRetain = GeneralLedger::where('client_id', $enter_item->client_id)
                ->where('profession_id', $enter_item->profession_id)
                ->where('date', '>=', $start_year)
                ->where('date', '<=', $end_year)
                ->where('chart_id', 999999)
                ->where('source', 'PBN')
                ->first();
        if ($isRetain) {
            $pl = GeneralLedger::where('client_id', $enter_item->client_id)
            ->where('profession_id', $enter_item->profession_id)
            ->where('transaction_id', $enter_item->tran_id)
            ->where('chart_id', 999998)->first();

            $rt['balance']  = $isRetain->balance - $pl->balance;

            if ($isRetain->credit != '') {
                $rt['credit'] = abs($rt['balance']);
                $rt['debit']  = 0;
            } else {
                $rt['debit']  = abs($rt['balance']);
                $rt['credit'] = 0;
            }
        }


        GeneralLedger::where('client_id', $enter_item->client_id)
        ->where('profession_id', $enter_item->profession_id)
        ->where('transaction_id', $enter_item->tran_id)
        ->where('chart_id', '!=', 999999)->delete();

        Gsttbl::where('client_id', $enter_item->client_id)
        ->where('profession_id', $enter_item->profession_id)
        ->where('trn_id', $enter_item->tran_id)->delete();

        Creditor::where('client_id', $enter_item->client_id)
        ->where('profession_id', $enter_item->profession_id)
        ->where('tran_id', $enter_item->tran_id)->delete();
        // Update Retain Data
        if ($isRetain) {
            $isRetain->update($rt);
        }

        try {
            toast('Invoice Delete successfully', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        // return redirect()->back();
        return redirect()->route('enter_item.manage');
    }
}
