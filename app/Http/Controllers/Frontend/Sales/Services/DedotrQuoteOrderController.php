<?php

namespace App\Http\Controllers\Frontend\Sales\Services;

use PDF;
use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Mail\QuoteViewableMail;
use App\Models\Frontend\Dedotr;
use App\Mail\InvoiceViewableMail;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Models\Frontend\Dedotr_job;
use App\Models\Frontend\Dedotr_qtc;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Frontend\CustomerCard;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\DedotrQuoteRequest;
use App\Models\Frontend\DedotrQuoteOrder;

class DedotrQuoteOrderController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.sales.quote.select_activity', compact('client'));
    }
    public function quote(Profession $profession)
    {
        $client  = Client::find(client()->id);
        $payment = $client->payment;
        $quation = DedotrQuoteOrder::whereClientId($client->id)
            ->whereProfessionId($profession->id)
            ->where('start_date', '>=', $payment->started_at->format('Y-m-d'))
            ->where('end_date', '<=', $payment->expire_at->format('Y-m-d'))
            ->count();
        if ($quation > $payment->sales_quotation) {
            toast('Quotation limit reached.', 'error');
            return redirect()->back();
        }

        $customers = CustomerCard::where('client_id', $client->id)->where('type', 1)->get();
        $codes     = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where(function ($q) {
                $q->where('code', 'like', '1%')
                    ->orWhere('code', 'like', '2%')
                    ->orWhere('code', 'like', '5%')
                    ->orWhere('code', 'like', '9%');
            })
            ->where('type', '2')
            ->orderBy('code')
            ->get();
        return view('frontend.sales.quote.service_quote', compact('client', 'customers', 'codes', 'profession'));
    }

    public function store(DedotrQuoteRequest $request)
    {
        $data         = $request->validated();
        $data['start_date'] = $date = makeBackendCompatibleDate($request->start_date);
        if ($request->end_date != '') {
            $data['end_date']   = $date = makeBackendCompatibleDate($request->end_date);
        }
        if (periodLock($request->client_id, $date)) {
            return response()->json('Your enter data period is locked, contact with administration', 403);
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
            DedotrQuoteOrder::create($data);
        }
        try {
            if(!$request->ajax()){
                toast('Debtor Quote Create success', 'success');
                return redirect()->route('quote.show', ['service', $request->inv_no, $request->client_id, $request->customer_card_id]);
            }else{
                $toast = ['message' => 'Debtor Quote Create success', 'status' => 200, 'inv_no' => DedotrQuoteOrder::whereClientId($request->client_id)->whereProfessionId($request->profession_id)->max('inv_no') + 1];
                return response()->json($toast);
            }

        } catch (\Exception $e) {
            $toast = ['message' => $e->getMessage(), 'status' => 500];
            return response()->json($toast);
        }
    }

    public function manage()
    {
        $client = client();
        $quotes = DedotrQuoteOrder::where('client_id', $client->id)
            ->where('source', 'quote')
            ->where('chart_id', 'not like', '551%')
            ->get()
            ->groupBy('inv_no');
        return view('frontend.sales.quote.manage', compact('client', 'quotes'));
    }

    // Edit ________________________________________
    public function edit(Request $request, $proId, $cusCardId, $inv_no)
    {
        $client   = client();
        if ($request->ajax()) {
            $quotes    = DedotrQuoteOrder::with(['client', 'customer'])
                ->whereClientId($client->id)
                ->whereProfessionId($proId)
                ->whereCustomerCard_id($cusCardId)
                ->where('inv_no', $inv_no)
                ->get();
            return response()->json(['quotes' => $quotes, 'status' => 200]);
        }
        $quotes    = DedotrQuoteOrder::with(['client', 'customer'])
            ->whereClientId($client->id)
            ->whereProfessionId($proId)
            ->whereCustomerCard_id($cusCardId)
            ->where('inv_no', $inv_no)
            ->get();

        if ($quotes->count() > 0) {
            $quote      = $quotes->first();
            $profession = Profession::find($proId);
            $customers  = CustomerCard::where('client_id', $client->id)->get();
            $codes      = ClientAccountCode::where('client_id', $client->id)
                ->whereProfessionId($proId)
                ->where(function ($q) {
                    $q->where('code', 'like', '1%')
                        ->orWhere('code', 'like', '2%')
                        ->orWhere('code', 'like', '5%')
                        ->orWhere('code', 'like', '9%');
                })
                ->where('type', '2')
                ->orderBy('code')
                ->get();
            return view('frontend.sales.quote.edit_quote', compact('codes', 'quote', 'quotes', 'customers', 'client', 'profession'));
        } else {
            toast('No Data found', 'error');
            return redirect()->route('quote.manage');
        }
    }

    public function update(Request $request, DedotrQuoteOrder $quote)
    {
        $data               = $request->except(['_token', '_method']);
        $data['start_date'] = $date = makeBackendCompatibleDate($request->start_date);
        $data['end_date']   = $date = makeBackendCompatibleDate($request->end_date);
        if (periodLock($request->client_id, $date)) {
            Alert::error('Your enter data period is locked, contact with administration');
            return back();
        }
        foreach ($request->job_title as $i => $jobTitle) {
            $dedotr = DedotrQuoteOrder::where('id', $request->inv_id[$i])->first();
            $rprice = $request->price[$i];
            if ($request->is_tax[$i] == 'yes') {
                $data['amount'] = $price = $rprice + ($rprice * 0.1);
            } else {
                $data['amount'] = $price = $rprice;
            }
            if ($request->has('disc_rate')) {
                $data['amount'] = $price = $price - ($price * ($request->disc_rate[$i] / 100));
                $data['disc_amount'] = ($rprice * ($request->disc_rate[$i] / 100));
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
            if ($dedotr != '') {
                $dedotr->update($data);
            } else {
                $data['disc_amount'] = $request->disc_amount[$i];
                $data['gst_amt'] = $request->gst_amt[$i];
                $data['totalamount'] = $request->totalamount[$i];
                $data['inv_id'] = $request->inv_id[$i];
                DedotrQuoteOrder::create($data);
            }
        }

        try {
            toast('Debtor Quote Order Updated success', 'success');
        } catch (\Exception $e) {
            // return $e->getMessage();
            toast('Something goes wrong!', 'error');
        }
        return redirect()->route('quote.manage', $quote->customer_card_id);
    }

    public function delete(Request $request)
    {
        $quote = DedotrQuoteOrder::find($request->id);
        if (periodLock($quote->client_id, $quote->end_date)) {
            Alert::error('Your enter data period is locked, contact with administration');
            return back();
        }
        try {
            $quote->delete();
            $message = ['message' => 'Debtor Quote Order deleted success', 'status' => '200'];
        } catch (\Exception $e) {
            $message = ['message' => $e->getMessage(), 'status' => '500'];
        }
        return response()->json($message);
    }

    public function destroy(DedotrQuoteOrder $quote, $proId, $cusCardId, $inv_no)
    {

        if (periodLock($quote->client_id, $quote->end_date)) {
            Alert::error('Your enter data period is locked, contact with administration');
            return back();
        }
        try {
            DedotrQuoteOrder::whereClientId($quote->client_id)
                ->whereProfessionId($proId)
                ->whereCustomerCard_id($cusCardId)
                ->where('inv_no', $inv_no)
                ->delete();
            toast('Debtor deleted success', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }

    public function templateStore(Request $request)
    {
        $data = $request->validate([
            "client_id" => 'required|integer',
            "title"     => 'required|string',
            "details"   => 'required|string',
            "type"      => 'required',
        ]);
        try {
            $data = Dedotr_qtc::create($data);
            return response()->json(['status' => 200, 'data' => $data]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function templateUpdate(Request $request)
    {
        $data = $request->validate([
            // "client_id" => 'required|integer',
            "title"     => 'required|string',
            "details"   => 'required|string',
            "type"      => 'required',
        ]);
        try {
            $data = Dedotr_qtc::find($request->id)->update($data);
            return response()->json(['status' => 200, 'data' => $data]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function templateShow()
    {
        $client = Client::find(client()->id);
        $dedotrs = Dedotr_qtc::where('client_id', $client->id)
            ->where('type', 1)->get();
        return response()->json(['status' => 200, 'dedotrs' => $dedotrs]);
    }

    public function templateDelete(Request $r)
    {
        Dedotr_qtc::findOrFail($r->id)->delete();
        return response()->json(['status' => 200, 'message' => 'Template Delete']);
    }

    public function jobStore(Request $request)
    {
        $data = $request->validate([
            "client_id"              => 'required|integer',
            "title"                  => 'required|string',
            "details"                => 'required|string',
            "client_account_code_id" => 'required|integer',
            "type"                   => 'required',
        ]);
        $client = Client::find($request->client_id);
        $code   = ClientAccountCode::find($request->client_account_code_id);
        if ($client->is_gst_enabled == 1 && ($code->gst_code == 'GST' || $code->gst_code == 'CAP' || $code->gst_code == 'INP')) {
            $data['is_tax'] = 'yes';
        } else {
            $data['is_tax'] = 'no';
        }
        try {
            $data = Dedotr_job::create($data);
            return response()->json(['status' => 200, 'data' => $data]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function jobUpdate(Request $request)
    {
        $data = $request->validate([
            // "client_id"              => 'required|integer',
            "title"                  => 'required|string',
            "details"                => 'required|string',
            "client_account_code_id" => 'required|integer',
        ]);
        $data['type']   = 1;
        $client         = Client::find($request->client_id);
        $code           = ClientAccountCode::find($request->client_account_code_id);
        $data['is_tax'] = ($client->is_gst_enabled == 1 && in_array($code->gst_code, ['GST', 'CAP', 'INP'])) ? 'yes' : 'no';
        // return$data = Dedotr_job::find($request->job_id);
        try {
            $data = Dedotr_job::find($request->job_id)->update($data);
            return response()->json(['status' => 200, 'data' => $data]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function jobShow()
    {
        $client = Client::find(client()->id);
        $jobs   = Dedotr_job::with('code')->where('client_id', $client->id)
            ->where('type', 1)->get();
        return response()->json(['status' => 200, 'jobs' => $jobs]);
    }
    public function jobDelete(Request $r)
    {
        Dedotr_job::findOrFail($r->id)->delete();
        return response()->json(['status' => 200, 'message' => 'Template Delete']);
    }



    public function convertInvoice()
    {
        $client    = client();
        $quotes    = DedotrQuoteOrder::with('customer')
            ->where('client_id', $client->id)
            ->where('source', 'quote')
            ->get()
            ->groupBy('inv_no');
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
        return view('frontend.sales.quote.convert_invoice', compact('client', 'quotes', 'codes'));
    }

    public function convertView($proId, $inv_no)
    {
        $dedotrs = DedotrQuoteOrder::whereClientId(client()->id)
            ->whereProfessionId($proId)
            ->where('inv_no', $inv_no)
            ->get();
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
        return view('frontend.sales.quote.convert_details', compact('dedotrs', 'codes'));
    }
    public function convertStore($proId, $inv_no)
    {
        $quotes  = DedotrQuoteOrder::whereClientId(client()->id)
            ->whereProfessionId($proId)
            ->where('inv_no', $inv_no)
            ->get();
        $request = $quotes->first();
        if (periodLock($request->client_id, $request->end_date)) {
            Alert::error('Your enter data period is locked, contact with administration');
            return back();
        }

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
        $dedos = Dedotr::where('client_id', $request->client_id)
            ->where('customer_card_id', $request->customer_card_id)
            ->get();
        // $tran_id = $request->client_id.$request->profession_id.$request->id.$request->customer_card_id.$request->start_date->format('dmy').rand(11, 99);
        $tran_id = transaction_id('QCI');
        $tran_date = $request->start_date->format('Y-m-d');

        $period = Period::where('client_id', $request->client_id)
            ->where('profession_id', $request->profession_id)
            // ->where('start_date', '<=', $request->start_date->format('Y-m-d'))
            ->where('end_date', '>=', $request->start_date->format('Y-m-d'))
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
            }
            $dedotrs = Dedotr::where('client_id', $request->client_id)
                ->where('tran_id', $tran_id)
                ->orderBy('chart_id')
                ->get();
            $gst = [
                'client_id'          => $request->client_id,
                'profession_id'      => $request->profession_id,
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
                $gst['source']  = 'QCI';
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

                $checksGst = Gsttbl::where('client_id', $request->client_id)->where('trn_id', $tran_id)->where('source', 'QCI')->get();

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
            $ledger['narration']      = $dedotr->first()->customer->name;
            $ledger['source']         = 'QCI';
            $ledger['client_id']      = $cid = $request->client_id;
            $ledger['profession_id']  = $pid = $request->profession_id;
            $ledger['transaction_id'] = $tran_id;
            $ledger['balance_type']   = 2;
            $ledger['debit']          = $ledger['credit'] = 0;


            $gstData = Gsttbl::where('client_id', $request->client_id)
                ->where('trn_id', $tran_id)
                ->where('trn_date', $tran_date)
                ->orderBy('chart_code')
                ->get();

            $codes = ClientAccountCode::where('client_id', $cid)
                ->where('profession_id', $pid)
                ->get();
            // Account code fron QCI
            foreach ($gstData->where('source', 'QCI') as $gd) {
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
            $ledger['balance']                = $ledger['debit'] = $dedotrs->sum('amount');
            $ledger['credit']                 = $ledger['gst']   = 0;
            GeneralLedger::create($ledger);

            // Gst Payable code
            $gstpay = $codes->where('code', 912100)->first();
            $ledger['balance_type']           = 2;
            $ledger['chart_id']               = $gstpay->code;
            $ledger['client_account_code_id'] = $gstpay->id;
            $ledger['balance']                = $ledger['credit'] = $gstData->sum('gst_accrued_amount');
            $ledger['debit']                 = $ledger['gst']   = 0;
            GeneralLedger::create($ledger);

            //RetailEarning Calculation
            // if (in_array($request->start_date->format('m'), range(1, 6))) {
            //     $start_year = $request->start_date->format('Y') - 1 . '-07-01';
            //     $end_year   = $request->start_date->format('Y') . '-06-30';
            // } else {
            //     $start_year = $request->start_date->format('Y') . '-07-01';
            //     $end_year   = $request->start_date->format('Y') + 1 . '-06-30';
            // }

            // $inRetain   = GeneralLedger::where('date', '>=', $start_year)
            //     ->where('date', '<=', $end_year)
            //     ->where('chart_id', 'LIKE', '1%')
            //     ->where('client_id', $request->client_id)
            //     ->where('source', 'QCI')
            //     ->get();
            // $retainData = $inRetain->where('balance_type', 2)->sum('balance') -
            //     $inRetain->where('balance_type', 1)->sum('balance');

            // $ledger['chart_id']               = 999999;
            // $ledger['client_account_code_id'] =
            //     $ledger['gst']                    = 0;
            // $ledger['balance']                = $retainData;
            // $ledger['credit']                 = abs($retainData);
            // $ledger['debit']                  = 0;
            // $ledger['balance_type']           = 1;
            // $ledger['source']                 = 'QCI';


            // $isRetain = GeneralLedger::where('date', '>=', $start_year)
            //     ->where('date', '<=', $end_year)
            //     ->where('chart_id', 999999)
            //     ->where('client_id', $request->client_id)
            //     ->where('source', 'QCI')->first();
            // if ($isRetain != null) {
            //     $isRetain->update($ledger);
            // } else {
            //     GeneralLedger::create($ledger);
            // }

            // Retain Earning For each Transection

            // $periodStartDate   = $period->start_date->format('Y-m-d');
            // $periodEndDate     = $period->end_date->format('Y-m-d');

            // $inTranRetain   = GeneralLedger::where('transaction_id', $tran_id)
            //     ->where('client_id', $request->client_id)
            //     ->where('profession_id', $request->profession_id)
            //     ->where('chart_id', 'LIKE', '1%')
            //     ->where('source', 'QCI')
            //     ->get();
            // $tranRetainData = $inTranRetain->where('balance_type', 2)->sum('balance') -
            //     $inTranRetain->where('balance_type', 1)->sum('balance');
            // $ledger['chart_id']               = 999998;
            // $ledger['gst']                    = 0;
            // $ledger['balance']                = $tranRetainData;
            // $ledger['credit']                 = abs($tranRetainData);

            // $isRetain = GeneralLedger::where('transaction_id', $tran_id)
            //     ->where('client_id', $request->client_id)
            //     ->where('profession_id', $request->profession_id)
            //     ->where('chart_id', 999998)
            //     ->where('source', 'QCI')->first();
            // if ($isRetain != null) {
            //     $isRetain->update($ledger);
            // } else {
            //     GeneralLedger::create($ledger);
            // }

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
            toast('Please check your accounting period from the Accounts>Add/Edit Entry', 'error');
            return back();
        }
    }
}
