<?php

namespace App\Actions\DataStore;

use Carbon\Carbon;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Data_storage;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Actions\RetainEarning;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DataStore
{
    public static function store(Request $request)
    {
        $date          = makeBackendCompatibleDate($request->date);
        $cdate         = $date->format('Y-m-d');
        $client_id     = $request->clientId;
        $profession_id = $request->professionId;
        $period_id     = $request->periodId;

        $dateValid = Period::where('start_date', '<=', $cdate)->where('end_date', '>=', $cdate)
            ->where('client_id', $request->clientId)
            ->where('profession_id', $request->professionId)
            ->first();
        if ($dateValid) {
            $gstamt      = $request->gstamt;
            $totalinv    = $request->totalinv;
            $percentile  = $request->percentile;
            $amount      = $request->amount;
            $startDate   = Carbon::parse($request->startDate)->format('dm');
            $endDate     = Carbon::parse($request->endDate)->format('dmy');
            // $tranId      = $startDate . $endDate . $request->clientId . $request->periodId;
            $tranId      = transaction_id('ADT');
            $gst_method  = $request->gst_method; // 0=None,2=Accrued,1=Cash
            $gst_enabled = $request->is_gst_enabled; // 1=YES , 0=NO
            $gst_code    = $request->gst_code; // GST,NILL,FREE,CAP,INP,etc
            $chartId     = $request->chartId; // Sub Sub Code
            $type        = $request->sub_cat_type; // 1=Debit , 2=Credit

            if (($type == 2 && $amount != $amount < 0) || ($type != 2 && $amount == $amount < 0) && empty($percentile)) { //GST Accrued Credit
                if (($gst_method == 2) && ($gst_enabled == 0)) {
                    $data['ac_type']           = $type;
                    $data['amount_credit']     = abs($amount);
                    $data['net_amount_credit'] = abs($amount);
                } elseif (($gst_method == 2) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                    $gst_ac_cr = $amount / 11;
                    $net_amt   = $amount - $gst_ac_cr;

                    $data['ac_type']            = $type;
                    $data['amount_credit']      = abs($amount);
                    $data['gst_accrued_credit'] = abs($gst_ac_cr);
                    $data['net_amount_credit']  = abs($net_amt);
                } elseif (($gst_method == 2) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                    $data['ac_type']           = $type;
                    $data['amount_credit']     = abs($amount);
                    $data['net_amount_credit'] = abs($amount);
                } elseif (($gst_method == 1) && ($gst_enabled == 0)) { // GST Cash Credit
                    $data['ac_type']           = $type;
                    $data['amount_credit']     = abs($amount);
                    $data['net_amount_credit'] = abs($amount);
                } elseif (($gst_method == 1) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                    $gst_ca_cr = $amount / 11;
                    $net_amt   = $amount - $gst_ca_cr;

                    $data['ac_type']           = $type;
                    $data['amount_credit']     = abs($amount);
                    $data['gst_cash_credit']   = abs($gst_ca_cr);
                    $data['net_amount_credit'] = abs($net_amt);
                } elseif (($gst_method == 1) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                    $data['ac_type']           = $type;
                    $data['amount_credit']     = abs($amount);
                    $data['net_amount_credit'] = abs($amount);
                } else {
                    $data['ac_type']           = $type;
                    $data['amount_credit']     = abs($amount);
                    $data['gst_cash_credit']   = abs($amount);
                    $data['net_amount_credit'] = abs($amount);
                }
            } elseif (($type == 1 && $amount != $amount < 0) || ($type != 1 && $amount == $amount < 0) && empty($percentile)) {// GST Accrued Debit
                if (($gst_method == 2) && ($gst_enabled == 0)) {
                    $data['ac_type']          = $type;
                    $data['amount_debit']     = abs($amount);
                    $data['net_amount_debit'] = abs($amount);
                } elseif (($gst_method == 2) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                    $gst_ac_deb = $amount / 11;
                    $net_amt    = $amount - $gst_ac_deb;

                    $data['ac_type']           = $type;
                    $data['amount_debit']      = abs($amount);
                    $data['gst_accrued_debit'] = abs($gst_ac_deb);
                    $data['net_amount_debit']  = abs($net_amt);
                } elseif (($gst_method == 2) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                    $data['ac_type']          = $type;
                    $data['amount_debit']     = abs($amount);
                    $data['net_amount_debit'] = abs($amount);
                } elseif (($gst_method == 1) && ($gst_enabled == 0)) {
                    $data['ac_type']           = $type;
                    $data['amount_debit']      = abs($amount);
                    $data['gst_accrued_debit'] = abs($amount);
                    $data['net_amount_debit']  = abs($amount);
                } elseif (($gst_method == 1) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) { // GST Cash Debit
                    $gst_ca_deb = $amount / 11;
                    $net_amt    = $amount - $gst_ca_deb;

                    $data['ac_type']          = $type;
                    $data['amount_debit']     = abs($amount);
                    $data['gst_cash_debit']   = abs($gst_ca_deb);
                    $data['net_amount_debit'] = abs($net_amt);
                } elseif (($gst_method == 1) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                    $data['ac_type']           = $type;
                    $data['amount_debit']      = abs($amount);
                    $data['net_amount_debit'] = abs($amount);
                } else {
                    $data['ac_type']          = $type;
                    $data['amount_debit']     = abs($amount);
                    $data['net_amount_debit'] = abs($amount);
                }
            }
            //If Amount and Persentense Insert
            if (!empty($amount) && !empty($percentile)) {
                $amount = $amount * ($percentile / 100);
                if (($type == 2 && $amount != $amount < 0) || ($type != 2 && $amount == $amount < 0)) {

                    if (($gst_method == 2) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                        $gst_ac_cr = $amount / 11;
                        $net_amt   = $amount - $gst_ac_cr;

                        $data['ac_type']            = $type;
                        $data['amount_credit']      = abs($amount);
                        $data['gst_accrued_credit'] = abs($gst_ac_cr);
                        $data['net_amount_credit']  = abs($net_amt);
                    } elseif ($gst_method == 2) {
                        $data['ac_type']           = $type;
                        $data['amount_credit']     = abs($amount);
                        $data['net_amount_credit'] = abs($amount);
                    } elseif (($gst_method == 1) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                        $gst_ca_cr = $amount / 11;
                        $net_amt   = $amount - $gst_ca_cr;

                        $data['ac_type']           = $type;
                        $data['amount_credit']     = abs($amount);
                        $data['gst_cash_credit']   = abs($gst_ca_cr);
                        $data['net_amount_credit'] = abs($net_amt);
                    } elseif ($gst_method == 1) {
                        $data['ac_type']           = $type;
                        $data['amount_credit']     = abs($amount);
                        $data['net_amount_credit'] = abs($amount);
                    } else {
                        $data['ac_type']           = $type;
                        $data['amount_credit']     = abs($amount);
                        $data['net_amount_credit'] = abs($amount);
                    }
                } elseif (($type == 1 && $amount != $amount < 0) || ($type != 1 && $amount == $amount < 0)) {
                    if (($gst_method == 2) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                        $gst_ac_de = $amount / 11;
                        $net_amt   = $amount - $gst_ac_de;

                        $data['ac_type']           = $type;
                        $data['amount_debit']      = abs($amount);
                        $data['gst_accrued_debit'] = abs($gst_ac_de);
                        $data['net_amount_debit']  = abs($net_amt);
                    } elseif ($gst_method == 2) {
                        $data['ac_type']          = $type;
                        $data['amount_debit']     = abs($amount);
                        $data['net_amount_debit'] = abs($amount);
                    } elseif (($gst_method == 1) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                        $gst_ca_de = $amount / 11;
                        $net_amt   = $amount - $gst_ca_de;

                        $data['ac_type']          = $type;
                        $data['amount_debit']     = abs($amount);
                        $data['gst_cash_debit']   = abs($gst_ca_de);
                        $data['net_amount_debit'] = abs($net_amt);
                    } elseif ($gst_method == 1) {
                        $data['ac_type']          = $type;
                        $data['amount_debit']     = abs($amount);
                        $data['net_amount_debit'] = abs($amount);
                    } else {
                        $data['ac_type']          = $type;
                        $data['amount_debit']     = abs($amount);
                        $data['net_amount_debit'] = abs($amount);
                    }
                }
            }
            //If GST T/INV % Insert
            if (!empty($gstamt) && !empty($totalinv) && empty($amount)) {
                if ($gstamt < 0 && $totalinv < 0) {
                    $gstamtf  = -1 * $gstamt;
                    $totalinv = -1 * $totalinv;
                    $grossamt = $gstamtf * 11;
                } else {
                    $grossamt = $gstamt * 11;
                }
                if ($grossamt < $totalinv && empty($percentile)) {
                    $amount       = $totalinv - $grossamt;
                    $grossamtmult = $gstamt * 11;
                } elseif (!empty($percentile) && $grossamt < $totalinv) {
                    $grossper     = $grossamt * ($percentile / 100);
                    $invper       = $totalinv * ($percentile / 100);
                    $amount       = $invper - $grossper;
                    $grossamtmult = $grossper;
                }
                // return $amount;
                if ($type == 1) {
                    if ($gstamt >= 0) {
                        if (($gst_method == 2) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                            $gst_ac_de = $grossamtmult / 11;
                            $net_amt   = $grossamtmult - $gst_ac_de;

                            $data['ac_type']           = $type;
                            $data['amount_debit']      = abs($grossamtmult);
                            $data['gst_accrued_debit'] = abs($gst_ac_de);
                            $data['net_amount_debit']  = abs($net_amt);
                            if (!empty($totalinv)) {
                                $data['balance'] = '-' . $amount;
                            }
                        } elseif ($gst_method == 2 && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                            $data['ac_type']          = $type;
                            $data['amount_debit']     = abs($grossamtmult);
                            $data['net_amount_debit'] = abs($grossamtmult);
                            if (!empty($totalinv)) {
                                $data['balance'] = '-' . $amount;
                            }
                        } elseif (($gst_method == 1) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                            $gst_ca_de = $grossamtmult / 11;
                            $net_amt   = $grossamtmult - $gst_ca_de;

                            $data['ac_type']          = $type;
                            $data['amount_debit']     = abs($grossamtmult);
                            $data['gst_cash_debit']   = abs($gst_ca_de);
                            $data['net_amount_debit'] = abs($net_amt);
                            if (!empty($totalinv)) {
                                $data['balance'] = '-' . $amount;
                            }
                        } elseif (($gst_method == 1) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                            $data['ac_type']          = $type;
                            $data['amount_debit']     = abs($grossamtmult);
                            $data['net_amount_debit'] = abs($grossamtmult);
                            if (!empty($totalinv)) {
                                $data['balance'] = '-' . $amount;
                            }
                        } else {
                            $data['ac_type']          = $type;
                            $data['amount_debit']     = abs($grossamtmult);
                            $data['net_amount_debit'] = abs($grossamtmult);
                            if (!empty($totalinv)) {
                                $data['balance'] = '-' . $amount;
                            }
                        }
                    } else {
                        if (($gst_method == 2) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                            $gst_ac_de = $grossamtmult / 11;
                            $net_amt   = $grossamtmult - $gst_ac_de;

                            $data['ac_type']            = $type;
                            $data['amount_credit']      = abs($grossamtmult);
                            $data['gst_accrued_credit'] = abs($gst_ac_de);
                            $data['net_amount_credit']  = abs($net_amt);
                            if (!empty($totalinv)) {
                                $data['balance'] = '-' . $amount;
                            }
                        } elseif ($gst_method == 2 && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                            $data['ac_type']           = $type;
                            $data['amount_credit']     = abs($grossamtmult);
                            $data['net_amount_credit'] = abs($grossamtmult);
                            if (!empty($totalinv)) {
                                $data['balance'] = '-' . $amount;
                            }
                        } elseif (($gst_method == 1) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                            $gst_ca_de = $grossamtmult / 11;
                            $net_amt   = $grossamtmult - $gst_ca_de;

                            $data['ac_type']           = $type;
                            $data['amount_credit']     = abs($grossamtmult);
                            $data['gst_cash_credit']   = abs($gst_ca_de);
                            $data['net_amount_credit'] = abs($net_amt);
                            if (!empty($totalinv)) {
                                $data['balance'] = '-' . $amount;
                            }
                        } elseif (($gst_method == 1) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                            $data['ac_type']           = $type;
                            $data['amount_credit']     = abs($grossamtmult);
                            $data['net_amount_credit'] = abs($grossamtmult);
                            if (!empty($totalinv)) {
                                $data['balance'] = '-' . $amount;
                            }
                        } else {
                            $data['ac_type']           = $type;
                            $data['amount_credit']     = abs($grossamtmult);
                            $data['net_amount_credit'] = abs($grossamtmult);
                            if (!empty($totalinv)) {
                                $data['balance'] = '-' . $amount;
                            }
                        }
                    }
                } elseif ($type == 2) {
                    if ($gstamt >= 0) {
                        if (($gst_method == 2) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                            $gst_ac_cr = $grossamtmult / 11;
                            $net_amt   = $grossamtmult - $gst_ac_cr;

                            $data['ac_type']            = $type;
                            $data['amount_credit']      = abs($grossamtmult);
                            $data['gst_accrued_credit'] = abs($gst_ac_cr);
                            $data['net_amount_credit']  = abs($net_amt);
                            if (!empty($totalinv)) {
                                $data['balance'] = abs($amount);
                            }
                        } elseif ($gst_method == 2 && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                            $data['ac_type']           = $type;
                            $data['amount_credit']     = abs($grossamtmult);
                            $data['net_amount_credit'] = abs($grossamtmult);
                            if (!empty($totalinv)) {
                                $data['balance'] = abs($amount);
                            }
                        } elseif (($gst_method == 1) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                            $gst_ca_cr = $grossamtmult / 11;
                            $net_amt   = $grossamtmult - $gst_ca_cr;

                            $data['ac_type']           = $type;
                            $data['amount_credit']     = abs($grossamtmult);
                            $data['gst_cash_credit']   = abs($gst_ca_cr);
                            $data['net_amount_credit'] = abs($net_amt);
                            if (!empty($totalinv)) {
                                $data['balance'] = abs($amount);
                            }
                        } elseif ($gst_method == 1 && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                            $data['ac_type']           = $type;
                            $data['amount_credit']     = abs($grossamtmult);
                            $data['net_amount_credit'] = abs($grossamtmult);
                            if (!empty($totalinv)) {
                                $data['balance'] = abs($amount);
                            }
                        } else {
                            $data['ac_type']           = $type;
                            $data['amount_credit']     = abs($grossamtmult);
                            $data['net_amount_credit'] = abs($net_amt);
                            if (!empty($totalinv)) {
                                $data['balance'] = abs($amount);
                            }
                        }
                    } else {
                        if (($gst_method == 2) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                            $gst_ac_cr = $grossamtmult / 11;
                            $net_amt   = $grossamtmult - $gst_ac_cr;

                            $data['ac_type']           = $type;
                            $data['amount_debit']      = abs($grossamtmult);
                            $data['gst_accrued_debit'] = abs($gst_ac_cr);
                            $data['net_amount_debit']  = abs($net_amt);
                            if (!empty($totalinv)) {
                                $data['balance'] = abs($amount);
                            }
                        } elseif ($gst_method == 2 && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                            $data['ac_type']          = $type;
                            $data['amount_debit']     = abs($grossamtmult);
                            $data['net_amount_debit'] = abs($grossamtmult);
                            if (!empty($totalinv)) {
                                $data['balance'] = abs($amount);
                            }
                        } elseif (($gst_method == 1) && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                            $gst_ca_cr = $grossamtmult / 11;
                            $net_amt   = $grossamtmult - $gst_ca_cr;

                            $data['ac_type']          = $type;
                            $data['amount_debit']     = abs($grossamtmult);
                            $data['gst_cash_debit']   = abs($gst_ca_cr);
                            $data['net_amount_debit'] = abs($net_amt);
                            if (!empty($totalinv)) {
                                $data['balance'] = abs($amount);
                            }
                        } elseif ($gst_method == 1 && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                            $data['ac_type']          = $type;
                            $data['amount_debit']     = abs($grossamtmult);
                            $data['net_amount_debit'] = abs($grossamtmult);
                            if (!empty($totalinv)) {
                                $data['balance'] = abs($amount);
                            }
                        } else {
                            $data['ac_type']          = $type;
                            $data['amount_debit']     = abs($grossamtmult);
                            $data['net_amount_debit'] = abs($net_amt);
                            if (!empty($totalinv)) {
                                $data['balance'] = abs($amount);
                            }
                        }
                    }
                }
            }
            $data['client_id']     = $client_id;
            $data['period_id']     = $period_id;
            $data['profession_id'] = $profession_id;
            $data['trn_id']        = $tranId;
            $data['trn_date']      = $date;
            $data['source']        = "ADT";
            $data['narration']     = $request->note;
            $data['chart_id']      = $chartId;
            $data['gst_code']      = $gst_code;
            $data['percent']       = $request->percentile;
            $data['total_inv']     = abs($request->totalinv);

            // dd($data);
            Data_storage::create($data);


            //Start Gst Table calculation
            // $c_Id = intval($chartId / 100000);
            // if (($c_Id == 1 || $c_Id == 2 || $c_Id == 5)) {
            //For GSTTBLS
            $gst['client_id']          = $client_id;
            $gst['period_id']          = $period_id;
            $gst['profession_id']      = $profession_id;
            $gst['trn_id']             = $tranId;
            $gst['trn_date']           = $date;
            $gst['chart_code']         = $chartId;
            $gst['source']             = 'ADT';
            $gst['gst_accrued_amount'] = 0;
            $gst['gst_cash_amount']    = 0;

            $amtDebit   = $amtCredit = $gstAD = $gstAC = $gstCD = $gstCC = $netAmtC = $netAmtD = 0;
            $incomeData = Data_storage::whereClientId($client_id)
                ->whereProfessionId($profession_id)
                ->where('trn_id', $tranId)
                ->where('chart_id', $chartId)
                ->where('period_id', $period_id)
                ->where('source', 'ADT')->get();
            foreach ($incomeData as $dbData) {
                $amtDebit  += $dbData->amount_debit;
                $amtCredit += $dbData->amount_credit;
                $gstAD     += $dbData->gst_accrued_debit;
                $gstAC     += $dbData->gst_accrued_credit;
                $gstCD     += $dbData->gst_cash_debit;
                $gstCC     += $dbData->gst_cash_credit;
                $netAmtD   += $dbData->net_amount_debit;
                $netAmtC   += $dbData->net_amount_credit;
            }

            if ($type == 1) {
                if (($gst_enabled == 0) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                    $gst['gross_amount'] = $amtDebit - $amtCredit;
                    $gst['net_amount']   = $netAmtD - $netAmtC;
                } elseif ($gst_method == 2) {
                    $gst['gross_amount']       = $amtDebit - $amtCredit;
                    $gst['gst_accrued_amount'] = $gstAD - $gstAC;
                    $gst['net_amount']         = $netAmtD - $netAmtC;
                } elseif ($gst_method == 1) {
                    $gst['gross_amount']    = $amtDebit - $amtCredit;
                    $gst['gst_cash_amount'] = $gstCD - $gstCC;
                    $gst['net_amount']      = $netAmtD - $netAmtC;
                }
            } elseif ($type == 2) {
                if (($gst_enabled == 0) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                    $gst['gross_amount'] = $amtCredit - $amtDebit;
                    $gst['net_amount']   = $netAmtC - $netAmtD;
                } elseif ($gst_method == 1) {
                    $gst['gross_amount']    = $amtCredit - $amtDebit;
                    $gst['gst_cash_amount'] = $gstCC - $gstCD;
                    $gst['net_amount']      = $netAmtC - $netAmtD;
                } elseif ($gst_method == 2) {
                    $gst['gross_amount']       = $amtCredit - $amtDebit;
                    $gst['gst_accrued_amount'] = $gstAC - $gstAD;
                    $gst['net_amount']         = $netAmtC - $netAmtD;
                }
            }
            $gstTblData = Gsttbl::whereClientId($client_id)
                ->whereProfessionId($profession_id)
                ->where('trn_id', $tranId)
                ->where('chart_code', $chartId)
                ->where('period_id', $period_id)
                ->where('source', 'ADT')->first();
            if ($gstTblData != null && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                $gstTblData->update($gst);
            } else {
                Gsttbl::create($gst);
            }

            //FOR general_ledgers Table
            $gst_amt = $gst['gst_accrued_amount'] == 0 ? $gst['gst_cash_amount'] : $gst['gst_accrued_amount'];

            $ledger ['date']                   = $payable['date']             = $date;
            $ledger ['narration']              = $payable['narration']        = $request->note;
            $ledger ['source']                 = $payable['source']           = 'ADT';
            $ledger ['client_id']              = $payable['client_id']        = $client_id;
            $ledger ['profession_id']          = $payable['profession_id']    = $profession_id;
            $ledger ['transaction_id']         = $payable['transaction_id']   = $tranId;
            $ledger ['chart_id']               = $payable['payable_liabilty'] = $chartId;
            $payable['loan']                   = $payable['gst']              = 0;
            $ledger ['client_account_code_id'] = $request->code_id;
            $ledger ['balance']                = $gst['net_amount'];
            $ledger ['gst']                    = abs($gst_amt);
            $payable['balance']                = $gst_amt;

            $payableAc = ClientAccountCode::where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->where('code', 912100)
                ->first();
            $clearingAc = ClientAccountCode::where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->where('code', 912101)
                ->first();

            if ($type == 1) {
                $ledger['debit']                   = abs($gst['gross_amount']);
                $payable['balance_type']           = $ledger['balance_type'] = 1;
                $payable['debit']                  = abs($gst_amt);
                $payable['chart_id']               = $clearingAc->code;
                $payable['client_account_code_id'] = $clearingAc->id;
                $payable['narration']              = 'ADT_CLEARING';

                if ($gst['net_amount'] < 0) {
                    $ledger ['debit']  = $payable['debit'] = 0;
                    $ledger ['credit'] = abs($gst['gross_amount']);
                    $payable['credit'] = abs($gst_amt);
                } else {
                    $ledger ['debit']  = abs($gst['gross_amount']);
                    $payable['debit']  = abs($gst_amt);
                    $ledger ['credit'] = $payable['credit'] = 0;
                }
            } elseif ($type == 2) {
                $ledger ['credit']                 = abs($gst['gross_amount']);
                $payable['balance_type']           = $ledger['balance_type'] = 2;
                $payable['credit']                 = abs($gst_amt);
                $payable['chart_id']               = $payableAc->code;
                $payable['client_account_code_id'] = $payableAc->id;
                $payable['narration']              = 'ADT_PAYABLE';

                if ($gst['net_amount'] < 0) {
                    $ledger ['credit']  = $payable['credit'] = 0;
                    $ledger ['debit'] = abs($gst['gross_amount']);
                    $payable['debit'] = abs($gst_amt);
                } else {
                    $ledger ['credit']  = abs($gst['gross_amount']);
                    $payable['credit']  = abs($gst_amt);
                    $ledger ['debit'] = $payable['debit'] = 0;
                }
            }


            // $ledgerData = GeneralLedger::where('client_id', $client_id)
            //     ->where('profession_id', $profession_id)
            //     ->where('transaction_id', $tranId)
            //     ->where('chart_id', $chartId)
            //     ->where('source', 'ADT')->first();
            // if ($ledgerData != null) {
            //     $ledgerData->update($ledger);
            // } else {
            //     GeneralLedger::create($ledger);
            // }
            GeneralLedger::create($ledger);
            //Payable
            if ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP') {
                GeneralLedger::create($payable);
            }
            //end Payable

            // Loan Form/Others Calculation
            $loanFrom = ClientAccountCode::where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->where('code', 954100)->first();
            $loanFromOthers = GeneralLedger::where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->where('transaction_id', $tranId)
                ->where('source', 'ADT')
                ->whereNotIn('chart_id', [912100, 912101, $loanFrom->code])
                ->where('chart_id', 'not like', '99999%')->get();

            $ledger['chart_id']               = $loanFrom->code;
            $ledger['client_account_code_id'] = $loanFrom->id;
            $ledger['debit']                  =
            $ledger['credit']                 =
            $ledger['gst']                    =
            $ledger['balance']                = 0;
            $ledger['balance_type']           = 2;
            $ledger['narration']              = 'ADT_LOAN';

            $loanDebit = $loanCredit = 0;
            if ($loanFromOthers->count()) {
                $loanDebit  = $loanFromOthers->sum('debit');
                $loanCredit = $loanFromOthers->sum('credit');
                $newLoan    = $loanDebit - $loanCredit;
                $ledger['balance']      = $newLoan;

                if ($newLoan > 1) {
                    $ledger['credit']       = abs($newLoan);
                    $ledger['debit']        = 0;
                } else {
                    $ledger['debit']        = abs($newLoan);
                    $ledger['credit']       = 0;
                }
            }
            $isLoan = GeneralLedger::where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->where('transaction_id', $tranId)
                ->where('chart_id', $loanFrom->code)
                ->where('source', 'ADT')->first();

            if ($isLoan != null) {
                $isLoan->update($ledger);
            } else {
                GeneralLedger::create($ledger);
            }

            //RetailEarning Calculation
            // RetainEarning::retain($request->clientId, $request->professionId, $date, $ledger, ['ADT', 'ADT']);
            // Retain Earning For each Transection
            // RetainEarning::tranRetain($request->clientId, $request->professionId, $tranId, $ledger, ['ADT', 'ADT']);
        //RetailEarning Calculation End....
        // }
        } else {
            Alert::error('Requested Date invalid', 'Please Select Correct Date');
        }
    }
    public static function destroy($dataStore)
    {
        $trn_id        = $dataStore->trn_id;
        $chart_code    = $dataStore->chart_id;
        $trn_date      = $dataStore->trn_date;
        $client_id     = $dataStore->client_id;
        $profession_id = $dataStore->profession_id;

        $gstTblData   = Gsttbl::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->where('trn_id', $trn_id)
            ->where('chart_code', $chart_code)->first();

        $dbDataStore = Data_storage::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->where('trn_id', $trn_id)
            ->where('chart_id', $chart_code)
            ->where('id', '!=', $dataStore->id)->get();

        $ledger = GeneralLedger::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->where('transaction_id', $trn_id)
            ->where('source', 'ADT')->get();
        if ($dataStore->ac_type == 2) {
            $data = [
                'gross_amount'       => $dbDataStore->sum('amount_credit') - $dbDataStore->sum('amount_debit'),
                'gst_accrued_amount' => $dbDataStore->sum('gst_accrued_credit') - $dbDataStore->sum('gst_accrued_debit'),
                'gst_cash_amount'    => $dbDataStore->sum('gst_cash_credit') - $dbDataStore->sum('gst_cash_debit'),
                'net_amount'         => $dbDataStore->sum('net_amount_credit') - $dbDataStore->sum('net_amount_debit'),
            ];
            $ledgerUpdate = [
                'credit'  => $data['gross_amount'],
                'gst'     => $data['gst_accrued_amount'] == '' ? $data['gst_cash_amount'] : $data['gst_accrued_amount'],
                'balance' => $data['net_amount']
            ];

            if ($dataStore->gst_code == 'GST' || $dataStore->gst_code == 'CAP' || $dataStore->gst_code == 'INP') {
                $payable = [
                    'credit' => $ledgerUpdate['gst'],
                    'balance' => $ledgerUpdate['gst'],
                ];
                $ledger->where('chart_id', 912100)->first()->update($payable);
            }
        } elseif ($dataStore->ac_type == 1) {
            $data = [
                'gross_amount'       => $dbDataStore->sum('amount_debit') - $dbDataStore->sum('amount_credit'),
                'gst_accrued_amount' => $dbDataStore->sum('gst_accrued_debit') - $dbDataStore->sum('gst_accrued_credit'),
                'gst_cash_amount'    => $dbDataStore->sum('gst_cash_debit') - $dbDataStore->sum('gst_cash_credit'),
                'net_amount'         => $dbDataStore->sum('net_amount_debit') - $dbDataStore->sum('net_amount_credit'),
            ];
            $ledgerUpdate = [
                'debit'  => $data['gross_amount'],
                'gst'     => $data['gst_accrued_amount'] == '' ? $data['gst_cash_amount'] : $data['gst_accrued_amount'],
                'balance' => $data['net_amount']
            ];

            if ($dataStore->gst_code == 'GST' || $dataStore->gst_code == 'CAP' || $dataStore->gst_code == 'INP') {
                $payable = [
                    'debit' => $ledgerUpdate['gst'],
                    'balance' => $ledgerUpdate['gst'],
                ];
                $ledger->where('chart_id', 912101)->first()->update($payable);
            }
        }
        // return $payable;
        //Update GstTbls data
        if ($gstTblData) {
            $gstTblData->update($data);
        }
        //Update Ledger Code
        $first_led = $ledger->where('chart_id', $chart_code)->first();
        if ($first_led) {
            $first_led->update($ledgerUpdate);
        }
        if ($dataStore) {
            $dataStore->delete();
        }
        // Loan Calculation
        $loan = $ledger->where('chart_id', 954100)->first();
        $loanOther = GeneralLedger::select(DB::raw('sum(credit) as credit,sum(debit) as debit'))
            ->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->where('transaction_id', $trn_id)
            ->where('source', 'ADT')
            ->whereNotIn('chart_id', ['912100','912101','954100','999999','999998'])
            ->first();
        $loanData =  abs($loanOther->credit) - abs($loanOther->debit);
        $loanUpdate['balance'] = $loanData;
        if ($loanData < 1) {
            $loanUpdate['credit'] = abs($loanData);
            $loanUpdate['debit']  = 0;
        } else {
            $loanUpdate['debit']  = abs($loanData);
            $loanUpdate['credit'] = 0;
        }
        if ($loan) {
            $loan->update($loanUpdate);
        }

        // // Tran Retain
        // $intData = GeneralLedger::select(DB::raw('sum(balance) as balance'))
        //     ->where('client_id', $client_id)
        //     ->where('profession_id', $profession_id)
        //     ->where('transaction_id', $trn_id)
        //     ->where('source', 'ADT')
        //     ->where('chart_id', 'LIKE', '1%')
        //     ->first();
        // $expData = GeneralLedger::select(DB::raw('sum(balance) as balance'))
        //     ->where('client_id', $client_id)
        //     ->where('profession_id', $profession_id)
        //     ->where('transaction_id', $trn_id)
        //     ->where('source', 'ADT')
        //     ->where('chart_id', 'LIKE', '2%')
        //     ->first();
        // // Pl Calculation
        // $pl        = $ledger->where('chart_id', 999998)->first();
        // $plData    = $intData->balance - $expData->balance;
        // $plUpdate['balance'] = $plData;
        // if ($intData->balance > $expData->balance) {
        //     $plUpdate['credit'] = abs($plData);
        //     $plUpdate['debit']  = 0;
        // } else {
        //     $plUpdate['debit']  = abs($plData);
        //     $plUpdate['credit'] = 0;
        // }
        // if ($pl) {
        //     $pl->update($plUpdate);
        // }

        // Retain
        // if ($trn_date->format('m') >= 07 & $trn_date->format('m') <= 12) {
        //     $start_year = $trn_date->format('Y') . '-07-01'; //2020-07-01==2021-06-30
        //     $end_year   = $trn_date->format('Y') + 1 . '-06-30';
        // } else {
        //     $start_year = $trn_date->format('Y') - 1 . '-07-01'; //2019-07-01==2020-06-30
        //     $end_year   = $trn_date->format('Y') . '-06-30';
        // }
        // $inRetain   = GeneralLedger::select(DB::raw('sum(balance) as balance'))
        //     ->where('client_id', $client_id)
        //     ->where('profession_id', $profession_id)
        //     ->where('date', '>=', $start_year)
        //     ->where('date', '<=', $end_year)
        //     ->where('chart_id', 'LIKE', '1%')
        //     ->first();
        // $expRetain   = GeneralLedger::select(DB::raw('sum(balance) as balance'))
        //     ->where('client_id', $client_id)
        //     ->where('profession_id', $profession_id)
        //     ->where('date', '>=', $start_year)
        //     ->where('date', '<=', $end_year)
        //     ->where('chart_id', 'LIKE', '2%')
        //     ->first();

        // // Reatin Calculation
        // $retain     = $ledger->where('chart_id', 999999)->first();
        // $retainData = $inRetain->balance - $expRetain->balance;
        // $retainUpdate['balance']  = $retainData;
        // if ($inRetain->balance > $expRetain->balance) {
        //     $retainUpdate['credit'] = abs($retainData);
        //     $retainUpdate['debit']  = 0;
        // } else {
        //     $retainUpdate['debit']  = abs($retainData);
        //     $retainUpdate['credit'] = 0;
        // }
        // if ($retain) {
        //     $retain->update($retainUpdate);
        // }

        GeneralLedger::where('client_id', $dataStore->client_id)
            ->where('profession_id', $dataStore->profession_id)
            ->where('transaction_id', $dataStore->trn_id)
            ->where('source', $dataStore->source)
            ->where('debit', '')
            ->where('credit', '')
            ->where('gst', '')
            ->where('balance', '')
            ->delete();
        Gsttbl::where('client_id', $dataStore->client_id)
            ->where('period_id', $dataStore->period_id)
            ->where('trn_id', $dataStore->trn_id)
            ->where('source', $dataStore->source)
            ->where('gross_amount', '')
            ->where('gst_accrued_amount', '')
            ->where('gst_cash_amount', '')
            ->where('net_amount', '')
            ->delete();
    }
}
