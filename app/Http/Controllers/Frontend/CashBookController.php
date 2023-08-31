<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Note;
use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Actions\RetainEarning;
use App\Models\ClientAccountCode;
use App\Models\Frontend\CashBook;
use Illuminate\Support\Facades\DB;
use App\Models\Frontend\CashOffice;
use App\Http\Controllers\Controller;
use App\Http\Requests\CashBookCreateRequest;

class CashBookController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.accounts.cash_book.select_activity', compact('client'));
    }
    public function office(Client $client, Profession $profession)
    {
        $offices = CashOffice::where('client_id', $client->id)->where('profession_id', $profession->id)->get();
        return view('frontend.accounts.cash_book.select_cash', compact('offices', 'client', 'profession'));
    }
    public function newoffice(Request $request)
    {
        $data = $request->validate([
            'client_id'     => 'required',
            'profession_id' => 'required',
            'name'          => 'required',
            'address'       => 'required',
        ]);
        try {
            CashOffice::create($data);
            toast('Office Created', 'success');
        } catch (\Exception $e) {
            #$e->getMessage();
            toast('something wrong', 'error');
        }
        return redirect()->back();
    }
    public function dataentry(Client $client, Profession $profession, CashOffice $office)
    {
        $cashbooks = CashBook::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('cash_office_id', $office->id)
            ->where('is_post', 0)
            ->latest()
            ->get();
        $openbl = CashBook::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('cash_office_id', $office->id)
            ->where('is_save', 1)
            ->where('is_post', 1)
            // ->where('tran_date', '<', now()->format('Y-m-d'))
            ->select(DB::raw('sum(amount_credit) as credit, sum(amount_debit) as debit'))
            ->first();
        $codes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', '!=', 551900)
            ->where('code', 'not like', '99999%')
            ->orderBy('code')
            ->get();
        $first_office = CashOffice::where('client_id', $client->id)
            ->where('profession_id', $profession->id)->first();
        $open_balance = 0;

        $note = Note::whereClientId($client->id)
            ->whereProfessionId($profession->id)
            ->where('model', 'cashbook')
            ->first();

        if ($first_office->id == $office->id) {
            $open_balance = GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('chart_id', 551900)
                ->where('date', '<', now()->format('Y-m-d'))
                ->sum('balance');
        }
        return view('frontend.accounts.cash_book.dataentry', compact('cashbooks', 'codes', 'office', 'client', 'profession', 'openbl', 'open_balance', 'first_office', 'note'));
    }

    public function store(CashBookCreateRequest $request)
    {
        $data      = $request->validated();
        $tran_date = $data['tran_date'] = now()->format('Y-m-d');
        if (periodLock($request->client_id, now())) {
            toast('Your enter data period is locked, check administration', 'warning');
            return response()->back();
        }
        $client = Client::find(client()->id);
        $period = Period::where('client_id', $client->id)
            ->where('profession_id', $request->profession_id)
            ->where('start_date', '<=', $tran_date)
            ->where('end_date', '>=', $tran_date)
            ->first();
        if (!$period) {
            toast('Period Not found', 'warning');
            return redirect()->back();
        }
        $cbooks = CashBook::where('client_id', $client->id)
            ->where('profession_id', $request->profession_id)
            ->where('cash_office_id', $request->cash_office_id)->get();
        $accum = $cbooks->sum('amount_credit') - $cbooks->sum('amount_debit');
        $data['period_id'] = $period->id;
        $amount = $request->payment ?? $request->recevied;
        $tranId = $data['tran_id'] = transaction_id('CBE');
        $gst_method  = $client->gst_method;      // 0=None,2=Accrued,1=Cash
        $gst_enabled = $client->is_gst_enabled;  // 1=YES , 0=NO
        $gst_code    = $request->gst_code;        // GST,NILL,FREE,CAP,INP,etc
        $chartId     = $request->chart_id;        // Sub Sub Code
        $gst_amt     = $request->gst;             // 1=Debit , 2=Credit

        if ($request->has('recevied') && $amount == $amount < 1) {
            $data['amt_type'] = $data['ac_type']  = 1;
        } elseif ($request->has('recevied') && $amount == $amount > 1) {
            $data['amt_type'] = $data['ac_type']  = 2;
        } elseif ($request->has('payment')  && $amount == $amount < 1) {
            $data['amt_type'] = $data['ac_type']  = 2;
        } elseif ($request->has('payment')  && $amount == $amount > 1) {
            $data['amt_type'] = $data['ac_type']  = 1;
        }
        $data['accumulated'] = $accum + $amount;
        if (($request->has('recevied') && $amount != $amount < 1) || ($request->has('payment')  && $amount == $amount < 1)) {
            //GST Accrued Credit
            if (($gst_method == 2) && ($gst_enabled == 0)) {
                $data['amount_credit']     = abs($amount);
                $data['net_amount_credit'] = abs($amount);
            } elseif (($gst_method == 2) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                $gst_ac_cr = $amount / 11;
                $net_amt   = $amount - $gst_ac_cr;
                $data['amount_credit']      = abs($amount);
                $data['gst_accrued_credit'] = abs($gst_ac_cr);
                $data['net_amount_credit']  = abs($net_amt);
            } elseif (($gst_method == 2) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                $data['amount_credit']     = abs($amount);
                $data['net_amount_credit'] = abs($amount);
            } elseif (($gst_method == 1) && ($gst_enabled == 0)) { // GST Cash Credit
                $data['amount_credit']     = abs($amount);
                $data['net_amount_credit'] = abs($amount);
            } elseif (($gst_method == 1) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                $gst_ca_cr = $amount / 11;
                $net_amt   = $amount - $gst_ca_cr;

                $data['amount_credit']     = abs($amount);
                $data['gst_cash_credit']   = abs($gst_ca_cr);
                $data['net_amount_credit'] = abs($net_amt);
            } elseif (($gst_method == 1) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                $data['amount_credit']     = abs($amount);
                $data['net_amount_credit'] = abs($amount);
            } else {
                $data['amount_credit']     = abs($amount);
                $data['gst_cash_credit']   = abs($amount);
                $data['net_amount_credit'] = abs($amount);
            }
        } elseif (($request->has('payment')  && $amount != $amount < 1) || ($request->has('recevied') && $amount == $amount < 1)) {
            // GST Accrued Debit
            if (($gst_method == 2) && ($gst_enabled == 0)) {
                $data['amount_debit']     = abs($amount);
                $data['net_amount_debit'] = abs($amount);
            } elseif (($gst_method == 2) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                $gst_ac_deb = $amount / 11;
                $net_amt    = $amount - $gst_ac_deb;

                $data['amount_debit']      = abs($amount);
                $data['gst_accrued_debit'] = abs($gst_ac_deb);
                $data['net_amount_debit']  = abs($net_amt);
            } elseif (($gst_method == 2) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                $data['amount_debit']     = abs($amount);
                $data['net_amount_debit'] = abs($amount);
            } elseif (($gst_method == 1) && ($gst_enabled == 0)) {
                $data['amount_debit']      = abs($amount);
                $data['gst_accrued_debit'] = abs($amount);
                $data['net_amount_debit']  = abs($amount);
            } elseif (($gst_method == 1) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) { // GST Cash Debit
                $gst_ca_deb = $amount / 11;
                $net_amt    = $amount - $gst_ca_deb;

                $data['amount_debit']     = abs($amount);
                $data['gst_cash_debit']   = abs($gst_ca_deb);
                $data['net_amount_debit'] = abs($net_amt);
            } elseif (($gst_method == 1) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                $data['amount_debit']      = abs($amount);
                $data['net_amount_debit'] = abs($amount);
            } else {
                $data['amount_debit']     = abs($amount);
                $data['net_amount_debit'] = abs($amount);
            }
        }
        CashBook::create($data);
        return redirect()->back();
    }

    public function massUpdate(Request $request)
    {
        // return $request->all();
        $cashbooks = CashBook::where('client_id', $request->client_id)
            ->where('profession_id', $request->profession_id)
            ->where('cash_office_id', $request->cash_office_id);
        $period = Period::where('client_id', $request->client_id)
            ->where('profession_id', $request->profession_id)
            // ->where('start_date', '<=', now()->format('Y-m-d'))
            ->where('end_date', '>=', now()->format('Y-m-d'))->first();

        DB::beginTransaction();
        // return $request->save;
        //         if ($request->has('save')) {
        //             $data = [
        //                 'tran_id' => transaction_id('CBE'),
        //                 'is_save' => 1
        //             ];
        //             $cashbooks->where('is_post', 0)->update($data);
        //             $msg = 'Cash Book Updated!';
        //         } else {

        $data = [
            'tran_id' => transaction_id('CBE'),
            'is_save' => 1
        ];
        $cashbooks->where('is_post', 0)->update($data);
        
        $casbks = $cashbooks->where('is_post', 0)->get();
        if ($casbks->count() > 0) {
            $codes = ClientAccountCode::where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)
                ->get();

            $payableAc     = $codes->where('code', 912100)->first();
            $clearingAc    = $codes->where('code', 912101)->first();
            $tranRetainEar = $codes->where('code', 999998)->first();
            $retainEar     = $codes->where('code', 999999)->first();
            $cashIn        = $codes->where('code', 551900)->first();
            $loanFrom      = $codes->where('code', 954100)->first();

            foreach ($casbks->groupBy(['gst_code', 'chart_id']) as $cashbook) {
                foreach ($cashbook as $cbook) {
                    // return $cbook->sum('net_amount_debit') - $cbook->sum('net_amount_credit');
                    $gst['client_id']     = $cbook->first()->client_id;
                    $gst['profession_id'] = $cbook->first()->profession_id;
                    $gst['period_id']     = $cbook->first()->period_id;
                    $gst['trn_id']        = $cbook->first()->tran_id;
                    $gst['trn_date']      = $cbook->first()->tran_date;
                    $gst['chart_code']    = $cbook->first()->chart_id;
                    $gst['source']        = 'CBE';

                    $code            = $codes->where('code', $cbook->first()->chart_id)->first();

                    if (str_starts_with($cbook->first()->chart_id, '9') || str_starts_with($cbook->first()->chart_id, '5')) {
                        $acca = $cbook->sum('gst_accrued_debit') - $cbook->sum('gst_accrued_credit');
                        $gca = $cbook->sum('gst_cash_debit') - $cbook->sum('gst_cash_credit');
                        $amount = $cbook->sum('amount_debit') - $cbook->sum('amount_credit');
                        $namount = $cbook->sum('net_amount_debit') - $cbook->sum('net_amount_credit');
                        if ($code->type == 1 && $amount > 0) {
                            $gst['gst_accrued_amount'] = abs($acca);
                            $gst['gst_cash_amount']    = abs($gca);
                            $gst['gross_amount']       = abs($amount);
                            $gst['net_amount']         = abs($namount);
                        } else {
                            $gst['gst_accrued_amount'] = $acca;
                            $gst['gst_cash_amount']    = $gca;
                            $gst['gross_amount']       = $amount;
                            $gst['net_amount']         = $namount;
                        }
                    } else {
                        $gst['gst_accrued_amount'] = $cbook->sum('gst_accrued_debit') > 0 ? $cbook->sum('gst_accrued_debit') : $cbook->sum('gst_accrued_credit');
                        $gst['gst_cash_amount']    = $cbook->sum('gst_cash_debit') > 0 ? $cbook->sum('gst_cash_debit') : $cbook->sum('gst_cash_credit');
                        $gst['gross_amount']       = $cbook->sum('amount_debit') > 0 ? $cbook->sum('amount_debit') : $cbook->sum('amount_credit');
                        $gst['net_amount']         = $cbook->sum('net_amount_debit') > 0 ? $cbook->sum('net_amount_debit') : $cbook->sum('net_amount_credit');
                    }
                    $gstData = Gsttbl::create($gst);

                    $code                = $codes->where('code', $gstData->chart_code)->first();
                    $ledger['date']                   = $pay['date']             = $gstData->trn_date;
                    $ledger['narration']              = $pay['narration']        = $cbook->first()->narration;
                    $ledger['source']                 = $pay['source']           = 'CBE';
                    $ledger['client_id']              = $pay['client_id']        = $request->client_id;
                    $ledger['profession_id']          = $pay['profession_id']    = $request->profession_id;
                    $ledger['transaction_id']         = $pay['transaction_id']   = $gstData->trn_id;
                    $ledger['chart_id']               = $pay['payable_liabilty'] = $code->code;
                    $ledger['client_account_code_id'] = $code->id;
                    $ledger['balance']                = $gstData->net_amount;

                    $ledger['gst'] = $pay['balance'] = $gstData->gst_accrued_amount > 0 ? abs($gstData->gst_accrued_amount) : abs($gstData->gst_cash_amount);
                    if ($cbook->first()->ac_type == $code->type) {
                        if ($code->type == 1 && $cbook->first()->ac_type == 1) {
                            $ledger['debit']               = abs($gstData->gross_amount);
                            $pay['debit']                  = abs($ledger['gst']);
                            $pay['chart_id']               = $clearingAc->code;
                            $pay['client_account_code_id'] = $clearingAc->id;
                            $ledger['balance_type']        = $pay['balance_type'] = 1;
                            $ledger['credit']              = 0;
                            $pay['narration']              = "CBE_CLEARING";
                        } else {
                            $ledger['credit']              = abs($gstData->gross_amount);
                            $pay['credit']                 = abs($ledger['gst']);
                            $pay['chart_id']               = $payableAc->code;
                            $pay['client_account_code_id'] = $payableAc->id;
                            $ledger['balance_type']        = $pay['balance_type'] = 2;
                            $ledger['debit']               = 0;
                            $pay['narration']              = "CBE_PAYABLE";
                        }
                    } else {
                        if ($code->type == 1 && $cbook->first()->ac_type == 1) {
                            $ledger['debit']               = abs($gstData->gross_amount);
                            $pay['debit']                  = abs($ledger['gst']);
                            $pay['chart_id']               = $clearingAc->code;
                            $pay['client_account_code_id'] = $clearingAc->id;
                            $ledger['balance_type']        = 2;
                            $pay['balance_type']           = 1;
                            $ledger['credit']              = 0;
                            $ledger['balance']             = $gstData->net_amount;
                            $pay['narration']              = "CBE_CLEARING";
                        } else {
                            $ledger['credit']              = abs($gstData->gross_amount);
                            $pay['credit']                 = abs($ledger['gst']);
                            $pay['chart_id']               = $payableAc->code;
                            $pay['client_account_code_id'] = $payableAc->id;
                            $ledger['balance_type']        = 1;
                            $pay['balance_type']           = 2;
                            $ledger['debit']               = 0;
                            $ledger['balance']             = $gstData->net_amount;
                            $pay['narration']              = "CBE_PAYABLE";
                        }
                    }

                    GeneralLedger::create($ledger);
                    if ($code->gst_code == 'GST' || $code->gst_code == 'CAP' || $code->gst_code == 'INP') {
                        GeneralLedger::create($pay);
                    }
                }
            }

            // Cash in hand calculations
            $ledger['chart_id']               = $cashIn->code;
            $ledger['client_account_code_id'] = $cashIn->id;
            $ledger['gst']                    = 0;
            $ledger['balance']                = $cdata = $request->cash_hand;
            $ledger['balance_type']           = 1;
            $ledger['narration']              = "CBE_BANK";
            if ($cdata < 1) {
                $ledger['credit']       = abs($cdata);
                $ledger['debit']        = 0;
            } else {
                $ledger['debit']  = abs($cdata);
                $ledger['credit'] = 0;
            }
            GeneralLedger::create($ledger);


            //RetailEarning Calculation
            // RetainEarning::retain($request->client_id, $request->profession_id, $casbks->first()->tran_date, $ledger, ['CBE', 'CBE']);
            // Retain Earning For each Transection
            // RetainEarning::tranRetain($request->client_id, $request->profession_id, $casbks->first()->tran_id, $ledger, ['CBE', 'CBE']);

            $cashbooks->where('is_post', 0)->update(['is_post' => 1]);
            $msg = 'Cash Book Posted!';
            // } else {
            //     $msg = 'Please save first!';
            // }
        }
        try {
            DB::commit();
            toast($msg, 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            toast($e->getMessage(), 'error');
        }
        return redirect()->route('cashbook.dataentry', [$request->client_id, $request->profession_id, $request->cash_office_id]);
    }
    public function edit(CashBook $cashbook)
    {
        //
    }
    public function update(Request $request, CashBook $cashbook)
    {
        //
    }
    public function destroy(CashBook $cashbook)
    {
        try {
            $cashbook->forceDelete();
            toast('Cashbook Deleted success', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }



    // Cashbook Report
    public function reportActivity()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.accounts.cash_book_report.select_activity', compact('client'));
    }

    public function reportOffice(Client $client, Profession $profession)
    {
        $offices = CashOffice::where('client_id', $client->id)->where('profession_id', $profession->id)->get();
        return view('frontend.accounts.cash_book_report.select_cash', compact('offices', 'client', 'profession'));
    }
    public function report(Request $request, Client $client, Profession $profession)
    {
        $start_date = makeBackendCompatibleDate($request->start_date);
        $end_date   = makeBackendCompatibleDate($request->end_date);
        $office     = CashOffice::findOrFail($request->office_id);
        $cashbooks  = CashBook::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('cash_office_id', $office->id)
            ->where('is_save', 1)
            ->where('is_post', 1)
            ->where('tran_date', '>=', $start_date->format('Y-m-d'))
            ->where('tran_date', '<=', $end_date->format('Y-m-d'))
            ->get();
        $codes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)->get();

        $openbl = CashBook::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('cash_office_id', $office->id)
            ->where('is_save', 1)
            ->where('is_post', 1)
            // ->where('tran_date', '<', now()->format('Y-m-d'))
            ->select(DB::raw('sum(amount_credit) as credit, sum(amount_debit) as debit'))
            ->first();
        $total_rcv = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('chart_id', 551900)
            ->where('date', '>=', $start_date->format('Y-m-d'))
            ->where('date', '<=', $end_date->format('Y-m-d'))->sum('debit');

        $first_office = CashOffice::where('client_id', $client->id)
            ->where('profession_id', $profession->id)->first();
        $open_balance = 0;
        if ($first_office->id == $office->id) {
            $open_balance = GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('chart_id', 551900)
                ->where('date', '<', $start_date->format('Y-m-d'))
                ->sum('balance');
        }
        return view('frontend.accounts.cash_book_report.report', compact('cashbooks', 'office', 'start_date', 'end_date', 'codes', 'openbl', 'client', 'profession', 'first_office', 'open_balance', 'total_rcv'));
    }
}
