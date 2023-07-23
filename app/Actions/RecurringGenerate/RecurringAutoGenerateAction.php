<?php

namespace App\Actions\RecurringGenerate;

use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\GeneralLedger;
use App\Actions\RetainEarning;
use App\Models\Frontend\Dedotr;
use App\Mail\InvoiceViewableMail;
use App\Models\ClientAccountCode;
use App\Models\Frontend\Recurring;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RecurringAutoGenerateAction
{
    public static function recurring()
    {
        $recurrings = Recurring::with(['customer','client','item'])->where('is_expire', 0)->get()->groupBy('tran_id');
        if ($recurrings->count() <= 0) {
            return 'Recurring not found!';
        }
        info('Recurring Auto Generate Start');
        $qt     = $recurrings->first()->first();
        $period = Period::where('client_id', $qt->client_id)
            ->where('profession_id', $qt->profession_id)
            // ->where('start_date', '<=', $qt->tran_date->format('Y-m-d'))
            ->where('end_date', '>=', $qt->tran_date->format('Y-m-d'))
            ->first();
        $tran_date = now()->format('Y-m-d');
        DB::beginTransaction();
        foreach ($recurrings as $recurs) {
            $recurring = $recurs->first();
            $customer  = $recurring->customer;
            $client    = $recurring->client;
            switch ($recurring->recurring) {
                case 1:
                    $frequency = $recurring->updated_at->addDay(); //daily
                    break;
                case 2:
                    $frequency = $recurring->updated_at->addWeek();  //weekly
                    break;
                case 3:
                    $frequency = $recurring->updated_at->addDays(14);  //Forthrightly
                    break;
                case 4:
                    $frequency = $recurring->updated_at->addWeek(4);  //Every four weeks
                    break;
                case 5:
                    $frequency = $recurring->updated_at->addMonth();   //Every monthly
                    break;
                case 6:
                    $frequency = $recurring->updated_at->addMonth(3); //Every three month
                    break;
                case 7:
                    $frequency = $recurring->updated_at->addYear();  //Every yearly
                    break;
                default:
                    $frequency = false;
                    break;
            }
            $condition = false;
            if ($frequency && $frequency->format('Ymd') == now()->format('Ymd')) {
                if ($recurring->untill_date && $recurring->untill_date->format('Ymd') > now()->format('Ymd')) {
                    $condition = true;
                } elseif ($recurring->unlimited) {
                    $condition = true;
                } elseif ($recurring->recur_tran > 0) {
                    $dedotrs = Dedotr::where('client_id', $qt->client_id)
                        ->where('profession_id', $qt->profession_id)
                        ->where('tran_id', $recurring->tran_id)->count();
                    if ($dedotrs < ($recurring->recur_tran - 1)) {
                        $condition = true;
                    } else {
                        return $condition = false;
                    }
                } else {
                    return $condition = false;
                }

                if ($condition) {
                    $recurs->each(fn ($q) => $q->update(['updated_at' => now()]));
                } else {
                    $recurs->each(fn ($q) => $q->update(['is_expire' => 1]));
                }
            }elseif($recurring->updated_at->format('Ymd') == now()->format('Ymd') && $recurring->is_start == 0){
                $condition = true;
                $recurs->each(fn ($q) => $q->update(['is_start' => 1]));
            }            
            
            if ($condition) {
                info('Condition True');
                $tran_id = transaction_id('RIV');
                $dedos = Dedotr::where('client_id', $recurring->client_id)
                            ->where('profession_id', $recurring->profession_id)
                            ->where('customer_card_id', $recurring->customer_card_id)->get();

                $inv_no = Dedotr::where('client_id', $recurring->client_id)->where('profession_id', $recurring->profession_id)->max('inv_no') + 1;
                $data["inv_no"]    = $inv_no;
                $data["tran_id"]   = $tran_id;
                $data["tran_date"] = $tran_date;
                foreach ($recurs as $recur) {
                    $data["client_id"]        = $recur->client_id;
                    $data["customer_card_id"] = $recur->customer_card_id;
                    $data["profession_id"]    = $recur->profession_id;
                    $data["your_ref"]         = $recur->your_ref;
                    $data["quote_terms"]      = $recur->quote_terms;
                    $data["item_no"]          = $recur->item_no;
                    $data["chart_id"]         = $recur->chart_id;
                    $data["job_title"]        = $recur->job_title;
                    $data["job_des"]          = $recur->job_des;
                    $data["item_name"]        = $recur->item_name;
                    $data["item_quantity"]    = $recur->item_quantity;
                    $data["hours"]            = $recur->hours;
                    $data["price"]            = $recur->price;
                    $data["disc_rate"]        = $recur->disc_rate;
                    $data["disc_amount"]      = $recur->disc_amount;
                    $data["freight_charge"]   = $recur->freight_charge;
                    $data["tax_rate"]         = $recur->tax_rate;
                    $data["amount"]           = $recur->amount;
                    $data["payment_no"]       = $recur->payment_no;
                    $data["payment_amount"]   = $recur->payment_amount;
                    $data["is_tax"]           = $recur->is_tax;
                    $data["recurring_id"]     = $recur->id;
                    $data["accum_amount"]     = $dedos->sum('amount') + $recur->amount;
                    Dedotr::create($data);
                }
                $dedotrs = Dedotr::where('client_id', $recurring->client_id)
                    ->where('profession_id', $recurring->profession_id)
                    ->where('tran_id', $tran_id)
                    ->orderBy('chart_id')
                    ->get();
                $gst = [
                    'client_id'          => $recurring->client_id,
                    'profession_id'      => $recur->profession_id,
                    'period_id'          => $period->id,
                    'trn_date'           => $tran_date,
                    'trn_id'             => $tran_id,
                    'source'             => 'RIV', //Recurring auto generate
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
                    $gst['source']  = 'RIV';
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

                    $checksGst = Gsttbl::where('client_id', $recurring->client_id)
                        ->where('profession_id', $recurring->profession_id)
                        ->where('trn_id', $tran_id)
                        ->where('source', 'RIV')->get();

                    // $checkGst = $checksGst->where('chart_code', $dedotr->first()->chart_id)->first();
                    // if ($checkGst != '') {
                    //     $checkGst->update($gst);
                    // } else {
                    Gsttbl::create($gst);
                    // }
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
                $ledger['narration']      = "Customer RAG";
                $ledger['source']         = 'RIV';
                $ledger['client_id']      = $cid = $recurring->client_id;
                $ledger['profession_id']  = $pid = $recurring->profession_id;
                $ledger['transaction_id'] = $tran_id;
                $ledger['balance_type']   = 2;
                $ledger['debit']          = $ledger['credit'] = 0;


                $gstData = Gsttbl::where('client_id', $recurring->client_id)
                    ->where('profession_id', $recurring->profession_id)
                    ->where('trn_id', $tran_id)
                    ->where('trn_date', $tran_date)
                    ->orderBy('chart_code')
                    ->get();

                $codes = ClientAccountCode::where('client_id', $cid)
                    ->where('profession_id', $pid)
                    ->get();
                // Account code fron RAG
                foreach ($gstData->where('source', 'RIV') as $gd) {
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
                RetainEarning::retain($cid, $pid, now(), $ledger, ['RIV', 'RIV']);
                // Retain Earning For each Transaction
                RetainEarning::tranRetain($cid, $pid, $tran_id, $ledger, ['RIV', 'RIV']);
                //RetailEarning Calculation End....
                
                // Send Email to Customer                
                if($recurring->mail_to){
                    Mail::to($recurring->mail_to)->send(new InvoiceViewableMail('service', $dedotrs->first(), $customer, $client));
                }else{
                    Mail::to($customer->email)->send(new InvoiceViewableMail('service', $dedotrs->first(), $customer, $client));
                }
                // /Send Email to Customer End
            }
        }
        try {
            DB::commit();
            info('Recurring Auto Generate END');
            return 'success';
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
