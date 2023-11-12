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
use App\Models\Frontend\InventoryItem;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\DedotrQuoteRequest;
use App\Models\Frontend\DedotrQuoteOrder;
use App\Models\Frontend\InventoryCategory;
use App\Models\Frontend\InventoryRegister;

class DedotrQuoteItemController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.sales.quote_item.select_activity', compact('client'));
    }
    public function quoteItem(Profession $profession)
    {
        $client  = Client::find(client()->id);
        $payment = $client->payment;
        $quation = DedotrQuoteOrder::whereClientId($client->id)
            ->where('start_date', '>=', $payment->started_at->format('Y-m-d'))
            ->where('end_date', '<=', $payment->expire_at->format('Y-m-d'))
            ->count();
        if ($quation > $payment->sales_quotation) {
            toast('Quotation limit reached.', 'error');
            return redirect()->back();
        }
        $customers = CustomerCard::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('name')
            ->where('type', 1)->get();
        $categories = InventoryCategory::with(['items' => function ($q) {
            $q->where('type', '!=', 1);
        }, 'items.code'])->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('parent_id', '!=', 0)
            ->get();

        $codes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', 'like', '1%')
            ->where('type', '2')
            ->orderBy('code')
            ->get();

        return view('frontend.sales.quote_item.service_quote', compact('client', 'customers', 'profession', 'categories', 'codes'));
    }
    public function store(DedotrQuoteRequest $request)
    {
        // return $request;
        $data          = $request->validated();
        $data['start_date'] = $date = makeBackendCompatibleDate($request->start_date);
        if ($request->end_date != '') {
            $data['end_date'] = $date = makeBackendCompatibleDate($request->end_date);
        }
        if (periodLock($request->client_id, $date)) {
            return response()->json('Your enter data period is locked, check administration', 500);
        }
        $data['tax_rate'] = 10;
        foreach ($request->chart_id as $i => $chart_id) {
            $data['chart_id']       = $chart_id;
            $data['item_no']        = $request->item_id[$i];
            $data['item_name']      = $request->item_name[$i];
            $data['item_quantity']  = $request->quantity[$i];
            $data['price']          = $request->amount[$i];
            $data['disc_rate']      = $request->disc_rate[$i];
            $data['disc_amount']    = $request->disc_amount[$i];
            $data['freight_charge'] = $request->freight_charge[$i];
            $data['is_tax']         = $request->is_tax[$i];
            $data['amount']         = $request->totalamount[$i];
            $data['ex_rate']        = $request->rate[$i];

            if ($request->is_tax[$i] == 'yes') {
                $data['tax_rate'] = 10;
            } else {
                $data['tax_rate'] = 0;
            }
            DedotrQuoteOrder::create($data);
        }
        try {
            $toast = ['message' => 'Dedotr Quote Item Create success', 'status' => 200, 'inv_no' => DedotrQuoteOrder::whereClientId($request->client_id)->whereProfessionId($request->profession_id)->max('inv_no') + 1];
            return response()->json($toast);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function manage()
    {
        $client = Client::find(client()->id);
        $quotes = DedotrQuoteOrder::where('client_id', $client->id)
            ->where('source', 'quote')
            ->where('chart_id', 'not like', '551%')
            ->get();
        return view('frontend.sales.quote_item.manage', compact('client', 'quotes'));
    }

    public function edit(Request $request, $proId, $cusCardId, $inv_no)
    {
        $quotes = DedotrQuoteOrder::with(['client', 'customer'])
            ->whereClientId(client()->id)
            ->whereProfessionId($proId)
            ->whereCustomerCardId($cusCardId)
            ->where('source', 'quote')
            ->where('inv_no', $inv_no)->get();
        if ($request->ajax()) {
            return response()->json(['quotes' => $quotes, 'status' => 200]);
        }
        if ($quotes->count() > 0) {
            $quote_order = $quotes->first();
            $client      = Client::find($quote_order->client_id);
            $customers   = CustomerCard::where('client_id', $client->id)
                ->where('profession_id', $quote_order->profession_id)
                ->where('type', 1)->get();
            $categories = InventoryCategory::with(['items' => function ($q) {
                $q->where('type', '!=', 1);
            }, 'items.code'])->where('client_id', $client->id)
                ->where('profession_id', $quote_order->profession_id)
                ->where('parent_id', '!=', 0)
                ->get();

            $codes = ClientAccountCode::where('client_id', $quote_order->client_id)
                ->where('profession_id', $quote_order->profession_id)
                ->where('code', 'like', '1%')
                ->where('type', '2')
                ->orderBy('code')
                ->get();


            return view('frontend.sales.quote_item.edit_quote', compact('quote_order', 'quotes', 'customers', 'client', 'categories', 'codes'));
        } else {
            toast('No Data found', 'error');
            return redirect()->route('quote.manage');
        }
    }

    public function update(Request $request, DedotrQuoteOrder $quote)
    {
        $data               = $request->except(['_token', '_method']);
        $data['start_date'] = $date = makeBackendCompatibleDate($request->start_date);
        if ($request->end_date != '') {
            $data['end_date'] = $date = makeBackendCompatibleDate($request->end_date);
        }
        if (periodLock($request->client_id, $date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        foreach ($request->item_name as $i => $itemName) {
            $dedotr = DedotrQuoteOrder::whereClientId($request->client_id)
                ->whereProfessionId($request->profession_id)
                ->whereCustomerCardId($request->customer_card_id)
                ->where('id', $request->inv_id[$i])
                ->first();

            $rprice = $request->amount[$i];
            if ($request->has('disc_rate')) {
                $data['amount']      = $price = $rprice - ($rprice * ($request->disc_rate[$i] / 100));
                $data['disc_amount'] = ($rprice * ($request->disc_rate[$i] / 100));
            }
            if ($request->has('freight_charge')) {
                $data['amount'] = $price = $price + $request->freight_charge[$i];
            }
            if ($request->is_tax[$i] == 'yes') {
                $data['amount'] = $price + ($price * 0.1);
            } else {
                $data['amount'] = $rprice;
            }
            $data['item_no']        = $request->item_id[$i];
            $data['item_name']      = $itemName;
            $data['item_quantity']  = $request->quantity[$i];
            $data['price']          = $rprice;
            $data['disc_rate']      = $request->disc_rate[$i];
            $data['freight_charge'] = $request->freight_charge[$i];
            $data['is_tax']         = $request->is_tax[$i];
            $data['chart_id']       = $request->chart_id[$i];
            $data['ex_rate']        = $request->rate[$i];


            if ($request->is_tax[$i] == 'yes') {
                $data['tax_rate'] = 10;
            } else {
                $data['tax_rate'] = 0;
            }

            if ($dedotr != '') {
                $dedotr->update($data);
            } else {
                $data['disc_amount'] = $request->disc_amount[$i];
                $data['gst_amt']     = $request->gst_amt[$i];
                $data['totalamount'] = $request->totalamount[$i];
                $data['inv_id']      = $request->inv_id[$i];
                DedotrQuoteOrder::create($data);
            }
        }

        try {
            toast('Dedotr Quote Order Updated success', 'success');
        } catch (\Exception $e) {
            Alert::error('Error','Something wrong!, please try again');
        }
        return redirect()->route('quote_item.manage', $quote->customer_card_id);
    }

    public function delete(Request $request)
    {
        $quote = DedotrQuoteOrder::find($request->id);
        if (periodLock($quote->client_id, $quote->end_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        try {
            $quote->delete();
            $message = ['message' => 'dedotr Quote Order deleted success', 'status' => '200'];
        } catch (\Exception $e) {
            $message = ['message' => $e->getMessage(), 'status' => '500'];
        }
        return response()->json($message);
    }

    public function destroy(DedotrQuoteOrder $quote, $proId, $cusCardId, $inv_no)
    {
        if (periodLock($quote->client_id, $quote->end_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        try {
            DedotrQuoteOrder::whereClientId(client()->id)
            ->whereProfessionId($proId)
            ->whereCustomerCardId($cusCardId)
            ->where('inv_no', $inv_no)
            ->delete();

            toast('Debtor deleted success', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }

    public function convertInvoice()
    {
        $client = Client::find(client()->id);
        $quotes = DedotrQuoteOrder::with('customer')
            ->where('client_id', $client->id)
            ->where('source', 'quote')
            ->get();
        $codes = ClientAccountCode::where('client_id', $client->id)
            ->where(function ($q) {
                $q->where('code', 'like', '1%')
                    ->orWhere('code', 'like', '2%')
                    ->orWhere('code', 'like', '5%')
                    ->orWhere('code', 'like', '9%');
            })
            ->where('type', '2')
            ->orderBy('code')
            ->get();
        return view('frontend.sales.quote_item.convert_invoice', compact('client', 'quotes', 'codes'));
    }

    public function convertView($inv_no)
    {
        $dedotrs = DedotrQuoteOrder::where('inv_no', $inv_no)->get();
        $codes   = ClientAccountCode::where('client_id', $dedotrs->first()->client_id)
            ->where(function ($q) {
                $q->where('code', 'like', '1%')
                    ->orWhere('code', 'like', '2%')
                    ->orWhere('code', 'like', '5%')
                    ->orWhere('code', 'like', '9%');
            })
            ->where('type', '2')
            ->orderBy('code')
            ->get();
        return view('frontend.sales.quote_item.convert_details', compact('dedotrs', 'codes'));
    }
    public function convertStore($inv_no)
    {
        $quotes      = DedotrQuoteOrder::where('inv_no', $inv_no)->get();
        $first_quote = $quotes->first();
        if (periodLock($first_quote->client_id, $first_quote->end_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }

        $client  = Client::find(client()->id);
        $payment = $client->payment;
        $quation = DedotrQuoteOrder::whereClientId($client->id)->where('start_date', '>=', $payment->started_at->format('Y-m-d'))->where('end_date', '<=', $payment->expire_at->format('Y-m-d'))->count();
        if ($quation > $payment->sales_quotation) {
            toast('Quotation limit reached.', 'error');
            return redirect()->back();
        }
        $dedos = Dedotr::where('client_id', $first_quote->client_id)
            ->where('customer_card_id', $first_quote->customer_card_id)->get();
        // $tran_id = $first_quote->client_id.$first_quote->profession_id.$first_quote->id.$first_quote->customer_card_id.$first_quote->start_date->format('dmy').rand(11, 99);
        $tran_id = transaction_id('QCI');

        $tran_date = $first_quote->start_date->format('Y-m-d');

        $period = Period::where('client_id', $first_quote->client_id)
            ->where('profession_id', $first_quote->profession_id)
            // ->where('start_date', '<=', $first_quote->start_date->format('Y-m-d'))
            ->where('end_date', '>=', $first_quote->start_date->format('Y-m-d'))
            ->first();

        DB::beginTransaction();
        if ($period != '') {
            foreach ($quotes as $quote) {
                $data["client_id"]        = $quote->client_id;
                $data["customer_card_id"] = $quote->customer_card_id;
                $data["profession_id"]    = $quote->profession_id;
                $data["chart_id"]         = $quote->chart_id;
                $data["inv_no"]           = str_pad($dedos->max('inv_no') + 1, 8, '0', STR_PAD_LEFT);
                $data["your_ref"]         = $quote->your_ref;
                $data["quote_terms"]      = $quote->quote_terms;
                $data["job_title"]        = $quote->job_title;
                $data["job_des"]          = $quote->job_des;
                $data["item_no"]          = $quote->item_no;
                $data["item_name"]        = $quote->item_name;
                $data["item_quantity"]    = $quote->item_quantity;
                $data["hours"]            = $quote->hours;
                $data["price"]            = $quote->price;
                $data["disc_rate"]        = $quote->disc_rate;
                $data["disc_amount"]      = $quote->disc_amount;
                $data["freight_charge"]   = $quote->freight_charge;
                $data["tax_rate"]         = $quote->tax_rate;
                $data["amount"]           = $quote->amount;
                $data["payment_no"]       = $quote->payment_no;
                $data["payment_amount"]   = $quote->payment_amount;
                $data["is_tax"]           = $quote->is_tax;
                $data["tran_id"]          = $tran_id;
                $data["tran_date"]        = $quote->start_date->format('Y-m-d');
                $data["accum_amount"]     = $dedos->sum('amount') + $quote->amount;
                Dedotr::create($data);

                // Inventory Register
                $inv_item            = InventoryItem::find($quote->item_no);
                $regData['client_id']         = $quote->client_id;
                $regData['profession_id']     = $quote->profession_id;
                $regData['inventory_item_id'] = $inv_item->id;
                $regData['source']            = 'sales';
                $regData['item_name']         = $item_name = Str::slug($inv_item->item_name . '-' . $inv_item->item_number);
                $regData['date']              = $data["tran_date"];
                $regData['sales_qty']         = $quote->item_quantity;
                $regData['sales_rate']        = $quote->ex_rate;
                // $invReg = InventoryRegister::where('client_id', $quote->client_id)
                //     ->where('profession_id', $quote->profession_id)
                //     ->where('item_name', $item_name)
                //     ->latest()->first();
                // if ($invReg != '') {
                //     $regData['close_qty']    = $invReg->close_qty - $quote->item_quantity;
                //     $regData['close_rate']   = $invReg->close_rate;
                //     $regData['close_amount'] = $invReg->close_rate * $regData['close_qty'];
                // }
                // $chkInvReg = $invReg->where('inventory_item_id', $inv_item->id)->first() ;
                // if ($chkInvReg!= '') {
                //     $chkInvReg->update($regData);
                // } else {
                InventoryRegister::create($regData);
                // }
            }
            $dedotrs = Dedotr::where('client_id', $first_quote->client_id)
                ->where('tran_id', $tran_id)
                ->orderBy('chart_id')
                ->get();
            $gst = [
                'client_id'          => $first_quote->client_id,
                'profession_id'      => $first_quote->profession_id,
                'period_id'          => $period->id,
                'trn_date'           => $tran_date,
                'trn_id'             => $tran_id,
                'source'             => 'QCI',
                'chart_code'         => 121100,
                'gross_amount'       => 0,
                'gross_cash_amount'  => 0,
                'gst_accrued_amount' => 0,
                'gst_cash_amount'    => 0,
                'net_amount'         => 0,
            ];
            foreach ($dedotrs->groupBy('chart_id') as $dedotr) {
                $gst['chart_code']   = $dedotr->first()->chart_id;
                $amount         = $dedotr->sum('amount');
                $price          = $dedotr->sum('price');
                $disc_rate      = $dedotr->sum('disc_rate') / $dedotr->count();
                $freight_charge = $dedotr->sum('freight_charge');
                $gst['source']       = 'QCI';
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

                $checksGst = Gsttbl::where('client_id', $first_quote->client_id)->where('trn_id', $tran_id)->where('source', 'QCI')->get();

                $checkGst = $checksGst->where('chart_code', $dedotr->first()->chart_id)->first();
                if ($checkGst != '') {
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

                if ($dedotr->first()->disc_rate != '') {
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
            $ledger['source']         = 'QCI';
            $ledger['client_id']      = $cid              = $first_quote->client_id;
            $ledger['profession_id']  = $pid              = $first_quote->profession_id;
            $ledger['transaction_id'] = $tran_id;
            $ledger['balance_type']   = 2;
            $ledger['debit']          = $ledger['credit'] = 0;


            $gstData = Gsttbl::where('client_id', $first_quote->client_id)
                ->where('trn_id', $tran_id)
                ->where('trn_date', $tran_date)
                ->orderBy('chart_code')
                ->get();

            $codes = ClientAccountCode::where('client_id', $cid)
                ->where('profession_id', $pid)
                ->get();
            // Account code fron QCI
            foreach ($gstData->where('source', 'QCI') as $gd) {
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
            $ledger['balance']                = $ledger['debit'] = $dedotrs->sum('amount');
            $ledger['credit']                 = $ledger['gst']   = 0;
            GeneralLedger::create($ledger);

            // Gst Payable code
            $gstpay                   = $codes->where('code', 912100)->first();
            $ledger['balance_type']           = 2;
            $ledger['chart_id']               = $gstpay->code;
            $ledger['client_account_code_id'] = $gstpay->id;
            $ledger['balance']                = $ledger['credit'] = $gstData->sum('gst_accrued_amount');
            $ledger['debit']                  = $ledger['gst']    = 0;
            GeneralLedger::create($ledger);

            //RetailEarning Calculation
            RetainEarning::retain($first_quote->client_id, $first_quote->profession_id, $first_quote->start_date, $ledger, ['QCI', 'QCI']);
            // Retain Earning For each Transection
            RetainEarning::tranRetain($first_quote->client_id, $first_quote->profession_id, $tran_id, $ledger, ['QCI', 'QCI']);
            //RetailEarning Calculation End....

            try {
                DB::commit();
                DedotrQuoteOrder::where('inv_no', $inv_no)->delete();
                toast('Quote Converted', 'success');
            } catch (\Exception $e) {
                DB::rollback();
                return $e;
                toast('something wrong!', 'error');
            }
            return redirect()->route('quote.convert');
        } else {
            toast('please check your accounting period from the Accouts>add/editperiod', 'error');
            return back();
        }
    }
}
