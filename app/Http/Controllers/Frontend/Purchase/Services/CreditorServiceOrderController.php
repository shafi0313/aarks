<?php

namespace App\Http\Controllers\Frontend\Purchase\Services;

use PDF;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Mail\OrderViewableMail;
use App\Mail\InvoiceViewableMail;
use App\Models\ClientAccountCode;
use App\Models\Frontend\Creditor;
use Illuminate\Support\Facades\DB;
use App\Models\Frontend\Dedotr_job;
use App\Models\Frontend\Dedotr_qtc;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Frontend\CustomerCard;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\DedotrQuoteRequest;
use App\Models\Frontend\CreditorServiceOrder;

class CreditorServiceOrderController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.purchase.service_order.select_activity', compact('client'));
    }

    public function service(Profession $profession)
    {
        $client  = Client::find(client()->id);
        $payment = $client->payment;
        $quation = CreditorServiceOrder::whereClientId($client->id)->where('start_date', '>=', $payment->started_at->format('Y-m-d'))->where('end_date', '<=', $payment->expire_at->format('Y-m-d'))->count();
        if ($quation > $payment->purchase_quotation) {
            toast('Service Order limit reached.', 'error');
            return redirect()->back();
        }
        $suppliers = CustomerCard::where('client_id', $client->id)
            ->where('profession_id', $profession->id)->where('type', 2)->get();
        $codes     = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where(function ($q) {
                $q->where('code', 'like', '2%')
                    ->orWhere('code', 'like', '56%');
            })
            ->where('type', '1')
            ->orderBy('code')
            ->get();
        return view('frontend.purchase.service_order.service_quote', compact('client', 'suppliers', 'codes', 'profession'));
    }

    public function templateShow()
    {
        $client = Client::find(client()->id);
        $creditts = Dedotr_qtc::where('client_id', $client->id)
            ->where('type', 2)->get();
        return response()->json(['status' => 200, 'dedotrs' => $creditts]);
    }

    public function jobShow()
    {
        $client = Client::find(client()->id);
        $jobs = Dedotr_job::where('client_id', $client->id)
            ->where('type', 2)->get();
        return response()->json(['status' => 200, 'jobs' => $jobs]);
    }

    public function store(DedotrQuoteRequest $request)
    {
        $data         = $request->validated();
        $data['start_date'] = $tran_date = makeBackendCompatibleDate($request->start_date);
        if ($request->end_date != '') {
            $data['end_date']   = makeBackendCompatibleDate($request->end_date);
        }
        if (periodLock($request->client_id, $tran_date)) {
            return response()->json('Your enter data period is locked, check administration', 500);
        }
        $data['tax_rate']   = 10;
        foreach ($request->chart_id as $i => $chart_id) {
            $data['chart_id']       = $chart_id;
            $data['disc_rate']      = $request->disc_rate[$i];
            $data['disc_amount']    = $request->disc_amount[$i];
            $data['freight_charge'] = $request->freight_charge[$i];
            $data['is_tax']         = $request->is_tax[$i];
            $data['job_des']        = $request->job_des[$i];
            $data['job_title']      = $request->job_title[$i];
            $data['price']          = $request->price[$i];
            $data['amount']         = $request->totalamount[$i];
            CreditorServiceOrder::create($data);
        }
        try {
            $toast = ['message' => 'Creditor Service Order Create success', 'status' => 200, 'inv_no' => CreditorServiceOrder::whereClientId($request->client_id)->whereProfessionId($request->profession_id)->max('inv_no') + 1];
        } catch (\Exception $e) {
            $toast = ['message' => $e->getMessage(), 'status' => 500];
        }
        return response()->json($toast);
    }
    // manage
    public function manage()
    {
        $client   = client();
        $services = CreditorServiceOrder::where('client_id', $client->id)
            ->where('chart_id', 'not like', '551%')->get();
        return view('frontend.purchase.service_order.manage', compact('client', 'services'));
    }

    public function edit(Request $request, $proId, $cusCardId, $inv_no)
    {
        $services    = CreditorServiceOrder::with(['client', 'customer'])
            ->whereClientId(client()->id)
            ->whereProfessionId($proId)
            ->whereCustomerCardId($cusCardId)
            ->where('inv_no', $inv_no)
            ->get();
        $service     = $services->first();
        if (periodLock($service->client_id, $service->start_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }

        if ($request->ajax()) {
            return response()->json(['services' => $services, 'status' => 200]);
        }
        if ($services->count() > 0) {
            $client    = Client::find($service->client_id);
            $suppliers = CustomerCard::where('client_id', $client->id)->get();
            $codes     = ClientAccountCode::where('client_id', $client->id)
                ->where(function ($q) {
                    $q->where('code', 'like', '1%')
                        ->orWhere('code', 'like', '2%')
                        ->orWhere('code', 'like', '5%')
                        ->orWhere('code', 'like', '9%');
                })
                ->where('type', '2')
                ->orderBy('code')
                ->get();
            return view('frontend.purchase.service_order.edit_service', compact('codes', 'service', 'services', 'suppliers', 'client'));
        } else {
            toast('No Data found', 'error');
            return redirect()->route('service_order.manage');
        }
    }

    public function update(Request $request, CreditorServiceOrder $service_order)
    {
        $data         = $request->except(['_token', '_method']);
        $data['start_date'] = $start_date = makeBackendCompatibleDate($request->start_date);
        $data['end_date']   = makeBackendCompatibleDate($request->end_date);
        if (periodLock($service_order->client_id, $start_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        foreach ($request->job_title as $i => $jobTitle) {
            $serv = CreditorServiceOrder::where('client_id', $service_order->client_id)
                ->where('profession_id', $service_order->profession_id)
                ->where('id', $request->inv_id[$i])->first();
            $rprice = $request->price[$i];
            if ($request->is_tax[$i] == 'yes') {
                $data['amount'] = $price = $rprice + ($rprice * 0.1);
            } else {
                $data['amount'] = $price = $rprice;
            }
            if ($request->has('disc_rate')) {
                $data['amount'] = $price = $price - ($price * ($request->disc_rate[$i] / 100));
            }
            if ($request->has('freight_charge')) {
                $data['amount'] = $price + $request->freight_charge[$i];
            }
            $data['job_title'] = $jobTitle;
            $data['job_des'] = $request->job_des[$i];
            $data['price'] = $rprice;
            $data['disc_rate'] = $request->disc_rate[$i];
            $data['freight_charge'] = $request->freight_charge[$i];
            $data['chart_id'] = $request->chart_id[$i];
            $data['is_tax'] = $request->is_tax[$i];
            if ($serv != '') {
                $serv->update($data);
            } else {
                $data['disc_amount'] = $request->disc_amount[$i];
                $data['gst_amt'] = $request->gst_amt[$i];
                $data['totalamount'] = $request->totalamount[$i];
                $data['inv_id'] = $request->inv_id[$i];
                CreditorServiceOrder::create($data);
            }
        }


        try {
            toast('Creditor Services Order Updated success', 'success');
        } catch (\Exception $e) {
            // return $e->getMessage();
            toast('Something goes wrong!', 'error');
        }
        return redirect()->route('service_order.manage', $service_order->customer_card_id);
    }

    public function delete(Request $request)
    {
        $service = CreditorServiceOrder::find($request->id);
        try {
            if (periodLock($service->client_id, $service->start_date)) {
                Alert::error('Your enter data period is locked, check administration');
                return back();
            }
            $service->delete();
            $message = ['message' => 'Creditor Service Order deleted success', 'status' => '200'];
        } catch (\Exception $e) {
            $message = ['message' => $e->getMessage(), 'status' => '500'];
        }
        return response()->json($message);
    }

    public function destroy($proId, $cusCardId, $inv_no)
    {
        $service_order = CreditorServiceOrder::whereClientId(client()->id)
            ->whereProfessionId($proId)
            ->whereCustomerCardId($cusCardId)
            ->where('inv_no', $inv_no);
        try {
            if (periodLock($service_order->first()->client_id, $service_order->first()->start_date)) {
                Alert::error('Your enter data period is locked, check administration');
                return back();
            }
            $service_order->delete();
            toast('service order deleted success', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }

    public function convertInvoice()
    {
        $client    = client();
        $services  = CreditorServiceOrder::with('customer')
            ->where('client_id', $client->id)
            ->where('source', 'PIV')
            ->get();

        $service     = $services->first();
        if (!$service) {
            Alert::error('No transaction found!');
            return back();
        }
        if (periodLock($service->client_id, $service->start_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        $codes     = ClientAccountCode::where('client_id', $client->id)
            ->where(function ($q) {
                $q->where('code', 'like', '2%')
                    ->orWhere('code', 'like', '56%');
            })
            ->where('type', '1')
            ->orderBy('code')
            ->get();
        return view('frontend.purchase.service_order.convert_invoice', compact('client', 'services', 'codes'));
    }
    public function convertView($inv_no)
    {
        $creditors = CreditorServiceOrder::where('inv_no', $inv_no)->get();

        $codes     = ClientAccountCode::where('client_id', $creditors->first()->client_id)
            ->where(function ($q) {
                $q->where('code', 'like', '2%')
                    ->orWhere('code', 'like', '56%');
            })
            ->where('type', '1')
            ->orderBy('code')
            ->get();

        return view('frontend.purchase.service_order.convert_details', compact('creditors', 'codes'));
    }
    public function convertStore($inv_no)
    {
        $creditors = CreditorServiceOrder::with(['client', 'customer'])->where('inv_no', $inv_no)->get();
        $qt        = $creditors->first();
        $profession = Profession::findOrFail($qt->profession_id);
        $is_freight = in_array('Services', $profession->industryCategories->pluck('name')->toArray());
        if (periodLock($qt->client_id, $qt->start_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        $credit    = Creditor::where('client_id', $qt->client_id)
            ->where('customer_card_id', $qt->customer_card_id)->get();
        // $tran_id   = $qt->client_id.$qt->profession_id.$qt->id.$qt->customer_card_id.$qt->start_date->format('dmy').rand(11, 99);
        $tran_id = transaction_id('SCI');
        $tran_date = $qt->start_date->format('Y-m-d');

        $period = Period::where('client_id', $qt->client_id)
            ->where('profession_id', $qt->profession_id)
            // ->where('start_date', '<=', $qt->start_date->format('Y-m-d'))
            ->where('end_date', '>=', $qt->start_date->format('Y-m-d'))
            ->first();

        DB::beginTransaction();
        if ($period != '') {
            foreach ($creditors as $creditor) {
                $data["client_id"]        = $creditor->client_id;
                $data["customer_card_id"] = $creditor->customer_card_id;
                $data["profession_id"]    = $creditor->profession_id;
                $data["chart_id"]         = $creditor->chart_id;
                $data["inv_no"]           = str_pad($credit->max('inv_no') + 1, 8, '0', STR_PAD_LEFT);
                $data["your_ref"]         = $creditor->your_ref;
                $data["quote_terms"]      = $creditor->quote_terms;
                $data["job_title"]        = $creditor->job_title;
                $data["job_des"]          = $creditor->job_des;
                $data["item_no"]          = $creditor->item_no;
                $data["item_name"]        = $creditor->item_name;
                $data["item_quantity"]    = $creditor->item_quantity;
                $data["hours"]            = $creditor->hours;
                $data["price"]            = $creditor->price;
                $data["disc_rate"]        = $creditor->disc_rate;
                $data["disc_amount"]      = $creditor->disc_amount;
                $data["freight_charge"]   = $creditor->freight_charge;
                $data["tax_rate"]         = $creditor->tax_rate;
                $data["amount"]           = $creditor->amount;
                $data["payment_no"]       = $creditor->payment_no;
                $data["payment_amount"]   = $creditor->payment_amount;
                $data["is_tax"]           = $creditor->is_tax;
                $data["tran_id"]          = $tran_id;
                $data["tran_date"]        = $creditor->start_date->format('Y-m-d');
                $data["accum_amount"]     = $credit->sum('amount') + $creditor->amount;
                Creditor::create($data);
            }
            $credits = Creditor::where('client_id', $qt->client_id)
                ->where('tran_id', $tran_id)
                ->orderBy('chart_id')
                ->get();
            $gst = [
                'client_id'          => $qt->client_id,
                'profession_id'      => $qt->profession_id,
                'period_id'          => $period->id,
                'trn_date'           => $tran_date,
                'trn_id'             => $tran_id,
                'source'             => 'SCI',
                'chart_code'         => 121100,
                'gross_amount'       => 0,
                'gross_cash_amount'  => 0,
                'gst_accrued_amount' => 0,
                'gst_cash_amount'    => 0,
                'net_amount'         => 0,
            ];
            foreach ($credits->groupBy('chart_id') as $creditt) {
                $gst['chart_code']   = $creditt->first()->chart_id;
                $amount         = $creditt->sum('amount');
                $price          = $creditt->sum('price');
                $disc_rate      = $creditt->sum('disc_rate') / $creditt->count();
                $freight_charge = $creditt->sum('freight_charge');
                $gst['source']  = 'SCI';
                if ($creditt->first()->is_tax == 'yes') {
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

                $checksGst = Gsttbl::where('client_id', $qt->client_id)->where('trn_id', $tran_id)->where('source', 'SCI')->get();

                $checkGst = $checksGst->where('chart_code', $creditt->first()->chart_id)->first();
                if ($checkGst != '') {
                    $checkGst->update($gst);
                } else {
                    Gsttbl::create($gst);
                }

                if ($is_freight && $creditt->first()->freight_charge != 0) {
                    $gst['gross_amount']       = $fFreight_charge;
                    $gst['gst_accrued_amount'] = $fgst;
                    $gst['net_amount']         = $fFreight_charge - $fgst;
                    if ($creditt->first()->is_tax == 'yes') {
                        $gst['chart_code'] = 242100;
                        $checkfr = $checksGst->where('chart_code', 242100)->first();
                        if ($checkfr != '') {
                            $gst['gross_amount']       = $checkfr->gross_amount + $fFreight_charge;
                            $gst['gst_accrued_amount'] = $checkfr->gst_accrued_amount + $fgst;
                            $gst['net_amount']         = $checkfr->net_amount + $fFreight_charge - $fgst;
                            $checkfr->update($gst);
                        } else {
                            Gsttbl::create($gst);
                        }
                    } else {
                        $gst['chart_code'] = 242101;
                        $checkfr = $checksGst->where('chart_code', 242101)->first();
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

                if ($creditt->first()->disc_rate != '') {
                    $gst['gross_amount']       = -$fDisc_rate;
                    $gst['gst_accrued_amount'] = -$dgst;
                    $gst['net_amount']         = -$fDisc_rate + $dgst;
                    if ($creditt->first()->is_tax == 'yes') {
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
            $ledger['source']         = 'SCI';
            $ledger['client_id']      = $cid = $qt->client_id;
            $ledger['profession_id']  = $pid = $qt->profession_id;
            $ledger['transaction_id'] = $tran_id;
            $ledger['balance_type']   = 1;
            $ledger['debit']          = $ledger['credit'] = 0;


            $gstData = Gsttbl::where('client_id', $qt->client_id)
                ->where('trn_id', $tran_id)
                ->where('trn_date', $tran_date)
                ->orderBy('chart_code')
                ->get();

            $codes = ClientAccountCode::where('client_id', $cid)
                ->where('profession_id', $pid)
                ->get();
            // Account code fron SCI
            foreach ($gstData->where('source', 'SCI') as $gd) {
                $code = $codes->where('code', $gd->chart_code)->first();
                $ledger['chart_id']               = $code->code;
                $ledger['client_account_code_id'] = $code->id;
                $ledger['balance']                = abs($gd->net_amount);
                $ledger['gst']                    = abs($gd->gst_accrued_amount);
                if ($code->code == 228998 || $code->code == 228999) {
                    $ledger['debit']        = 0;
                    $ledger['credit']       = abs($gd->gross_amount);
                    $ledger['balance_type'] = 2;
                } else {
                    $ledger['balance_type'] = 1;
                    $ledger['credit']       = 0;
                    $ledger['debit']        = abs($gd->gross_amount);
                }
                GeneralLedger::create($ledger);
            }
            // Trade Creditor code
            $trade = $codes->where('code', 911999)->first();
            $ledger['balance_type']           = 2;
            $ledger['chart_id']               = $trade->code;
            $ledger['client_account_code_id'] = $trade->id;
            $ledger['balance']                = $ledger['credit'] = $credits->sum('amount');
            $ledger['debit']                  = $ledger['gst']    = 0;
            GeneralLedger::create($ledger);

            // Gst Clear code
            $gstClear = $codes->where('code', 912101)->first();
            $ledger['balance_type']           = 1;
            $ledger['chart_id']               = $gstClear->code;
            $ledger['client_account_code_id'] = $gstClear->id;
            $ledger['balance']                = $ledger['debit'] = $gstData->sum('gst_accrued_amount');
            $ledger['credit']                 = $ledger['gst']   = 0;
            GeneralLedger::create($ledger);

            //RetailEarning Calculation
            if (in_array($qt->start_date->format('m'), range(1, 6))) {
                $start_year = $qt->start_date->format('Y') - 1 . '-07-01';
                $end_year   = $qt->start_date->format('Y') . '-06-30';
            } else {
                $start_year = $qt->start_date->format('Y') . '-07-01';
                $end_year   = $qt->start_date->format('Y') + 1 . '-06-30';
            }
            $inRetain   = GeneralLedger::where('date', '>=', $start_year)
                ->where('date', '<=', $end_year)
                ->where('chart_id', 'LIKE', '2%')
                ->where('client_id', $qt->client_id)
                ->where('source', 'SCI')
                ->get();
            $retainData = $inRetain->where('balance_type', 2)->sum('balance') -
                $inRetain->where('balance_type', 1)->sum('balance');

            $ledger['chart_id']               = 999999;
            $ledger['client_account_code_id'] =
                $ledger['gst']                    = 0;
            $ledger['balance']                = $retainData;
            $ledger['credit']                 = abs($retainData);
            $ledger['debit']                  = 0;
            $ledger['balance_type']           = 1;
            $ledger['source']                 = 'SCI';


            $isRetain = GeneralLedger::where('date', '>=', $start_year)
                ->where('date', '<=', $end_year)
                ->where('chart_id', 999999)
                ->where('client_id', $qt->client_id)
                ->where('source', 'SCI')->first();
            if ($isRetain != null) {
                $isRetain->update($ledger);
            } else {
                GeneralLedger::create($ledger);
            }

            // Retain Earning For each Transection

            $periodStartDate   = $period->start_date->format('Y-m-d');
            $periodEndDate     = $period->end_date->format('Y-m-d');

            $inTranRetain   = GeneralLedger::where('transaction_id', $tran_id)
                ->where('client_id', $qt->client_id)
                ->where('profession_id', $qt->profession_id)
                ->where('chart_id', 'LIKE', '2%')
                ->where('client_id', $qt->client_id)
                ->where('source', 'SCI')
                ->get();
            $tranRetainData = $inTranRetain->where('balance_type', 2)->sum('balance') -
                $inTranRetain->where('balance_type', 1)->sum('balance');
            $ledger['chart_id']               = 999998;
            $ledger['gst']                    = 0;
            $ledger['balance']                = $tranRetainData;
            $ledger['credit']                 = abs($tranRetainData);

            $isRetain = GeneralLedger::where('transaction_id', $tran_id)
                ->where('client_id', $qt->client_id)
                ->where('profession_id', $qt->profession_id)
                ->where('chart_id', 999998)
                ->where('source', 'SCI')->first();
            if ($isRetain != null) {
                $isRetain->update($ledger);
            } else {
                GeneralLedger::create($ledger);
            }

            //RetailEarning Calculation End....

            try {
                DB::commit();
                CreditorServiceOrder::where('inv_no', $inv_no)->delete();
                toast('Service Converted', 'success');
            } catch (\Exception $e) {
                DB::rollback();
                return $e;
                toast('something wrong!', 'error');
            }
            return redirect()->route('service_bill.manage');
        } else {
            toast('please check your accounting period from the Accounts>add/edit period', 'error');
            return back();
        }
    }
}
