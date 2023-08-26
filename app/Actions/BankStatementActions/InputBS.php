<?php

namespace App\Actions\BankStatementActions;

use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Actions\RetainEarning;
use App\Models\ClientAccountCode;
use App\Models\BankStatementInput;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class InputBS
{
    public function post(Request $request)
    {
        $bankAccount  = ClientAccountCode::findOrFail($request->bank_account);
        $client       = Client::findOrFail($request->client_id);
        $profession   = Profession::findOrFail($request->profession_id);
        $gstMethod    = $client->gst_method;       // 0=None,1=Cash, 2=Accrued,
        $gstEnabled   = $client->is_gst_enabled;  // 1=YES , 0=NO


        // $bank_statements = BankStatementInput::select('*', DB::raw("DATE_FORMAT(LAST_DAY(date),'%d%m%y') as last_date, DATE_FORMAT(date,'01%m') AS first_date"))
        // $bank_statements = BankStatementInput::with('client_account_code')
        //     ->where('client_id', $client->id)
        //     ->where('profession_id', $profession->id)
        //     ->where('is_posted', 0)
        //     ->where('account_code', '!=', null)
        //     ->get();

        $raw_statements = BankStatementInput::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('is_posted', 0)
            ->whereNotNull('account_code')
            ->get();

        // Check if all dates in the collection belong to the same month
        $month = null;
        if (!$raw_statements->every(function ($statement) use (&$month) {
            $statement_month = $statement->date->month;
            if (is_null($month)) { // First statement in collection
                $month = $statement_month;
                return true;
            } else {
                return $statement_month === $month;
            }
        })) {
            Alert::error('Please Post Single Month Bank Statements', 'Bank statements contain dates from multiple months.');
            return redirect()->back();
        }

        // Filter the collection to include only statements from the target month
        $bank_statements = BankStatementInput::with('client_account_code')
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('is_posted', 0)
            ->whereNotNull('account_code')
            ->whereMonth('date', $month)
            ->get();

        // Check Period Lock
        if (periodLock($client->id, $bank_statements->first()->date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        $period = Period::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            // ->where('start_date', '<=', $bank_statements->first()->date)
            ->where('end_date', '>=', $bank_statements->first()->date)->first();
        if (!$period) {
            Alert::error('Period Not Found Please make sure you have period on this date');
            return back();
        }
        DB::beginTransaction();

        if ($bank_statements->count()) {
            $tran_id  = transaction_id('INP');
            foreach ($bank_statements as $bank_statement) {
                $tran_date = $bank_statement->date;
                $debit     = $bank_statement->debit;
                $credit    = $bank_statement->credit;

                $code      = $bank_statement->client_account_code;
                $chart_id  = $code->code;
                $type      = $code->type; // 1=Debit , 2=Credit
                $gst_code  = $code->gst_code; // GST,NILL,FREE,CAP,INP

                if ($type == 1) {
                    $debit  = $debit == 0 ? -$credit : $debit;
                // if (substr($chart_id, -6, 1) == 1) {
                    //     $debit = - $debit;
                // }
                } else {
                    $credit = $credit == 0 ? -$debit : $credit;
                    // if (substr($chart_id, -6, 1) == 2) {
                    //     $credit = - $credit;
                    // }
                }
                // GST Table
                $gst['client_id']          = $client->id;
                $gst['profession_id']      = $profession->id;
                $gst['period_id']          = $period->id;
                $gst['trn_id']             = $tran_id;
                $gst['trn_date']           = $tran_date;
                $gst['chart_code']         = $chart_id;
                $gst['source']             = 'INP';
                $gst['gross_amount']       = 
                $gst['gst_accrued_amount'] = $gst['gst_cash_amount'] = $gst['net_amount'] = 0;
                if ($type == 1) { //Debit
                    $gst['gross_amount'] = $gst['net_amount'] = $debit;
                    if ($gstEnabled == 1) {
                        if ($gstMethod == 1 && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) { //Cash
                            $gst['gst_accrued_amount'] = 0;
                            $gst['gst_cash_amount']    = $debit / 11;
                            $gst['net_amount']         = $debit - ($debit / 11);
                        } elseif ($gstMethod == 2 && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) { //Accrud
                            $gst['gst_accrued_amount'] = $debit / 11;
                            $gst['gst_cash_amount']    = 0;
                            $gst['net_amount']         = $debit - ($debit / 11);
                        } else {
                            $gst['gst_accrued_amount'] = 0;
                            $gst['gst_cash_amount']    = 0;
                        }
                    } else { //gst disabled
                        $gst['gst_accrued_amount'] = 0;
                        $gst['gst_cash_amount']    = 0;
                    }
                } else { //credit
                    $gst['gross_amount'] = $gst['net_amount'] = $credit;
                    if ($gstEnabled == 1) {
                        if ($gstMethod == 1 && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) { //Cash
                            $gst['gst_accrued_amount'] = 0;
                            $gst['gst_cash_amount']    = $credit / 11;
                            $gst['net_amount']         = $credit - ($credit / 11);
                        } elseif ($gstMethod == 2 && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) { //Accrud
                            $gst['gst_accrued_amount'] = $credit / 11;
                            $gst['gst_cash_amount']    = 0;
                            $gst['net_amount']         = $credit - ($credit / 11);
                        } else { //Accrud
                            $gst['gst_accrued_amount'] = 0;
                            $gst['gst_cash_amount']    = 0;
                        }
                    } else { //gst disabled
                        $gst['gst_accrued_amount'] = 0;
                        $gst['gst_cash_amount']    = 0;
                    }
                }
                Gsttbl::create($gst);

                $ledger['chart_id']               = $chart_id;
                $ledger['date']                   = $tran_date;
                $ledger['narration']              = $bank_statement->narration;
                $ledger['source']                 = 'INP';
                $ledger['client_id']              = $client->id;
                $ledger['profession_id']          = $profession->id;
                $ledger['transaction_id']         = $tran_id;
                $ledger['client_account_code_id'] = $bank_statement->account_code;
                $ledger['balance']                = $gst['net_amount'];
                $ledger['debit']                  = $ledger['credit'] = 0;
                $ledger['gst']                    = $gst['gst_accrued_amount'] == 0 ? $gst['gst_cash_amount'] : $gst['gst_accrued_amount'];

                // if ($type == 1) {
                //     $ledger['debit']        = abs($gst['gross_amount']);
                //     $ledger['credit']       = 0;
                //     $ledger['balance_type'] = 1;
                // } elseif ($type == 2) {
                //     $ledger['debit']        = 0;
                //     $ledger['credit']       = abs($gst['gross_amount']);
                //     $ledger['balance_type'] = 2;
                // }

                if ($type == 1) {
                    $ledger['debit']        = $gst['gross_amount'] > 0 ? abs($gst['gross_amount']) : 0;
                    $ledger['credit']       = $gst['gross_amount'] < 0 ? abs($gst['gross_amount']) : 0;
                    $ledger['balance_type'] = 1;
                } elseif ($type == 2) {
                    $ledger['debit']        = $gst['gross_amount'] < 0 ? abs($gst['gross_amount']) : 0;
                    $ledger['credit']       = $gst['gross_amount'] > 0 ? abs($gst['gross_amount']) : 0;
                    $ledger['balance_type'] = 2;
                }
                GeneralLedger::create($ledger);

                if ($gstEnabled == 1 && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                    //! Payable Clearing calculation
                    $payableAc = ClientAccountCode::where('client_id', $client->id)
                        ->where('profession_id', $profession->id)
                        ->where('code', 912100)
                        ->first();
                    $clearingAc = ClientAccountCode::where('client_id', $client->id)
                        ->where('profession_id', $profession->id)
                        ->where('code', 912101)
                        ->first();
                    $ledger['payable_liabilty'] = $chart_id;
                    if ($type == 1) {
                        $ledger['balance_type']           = 1;
                        $ledger['debit']                  = $ledger['gst'] > 0 ? abs($ledger['gst']) : 0;
                        $ledger['credit']                 = $ledger['gst'] < 0 ? abs($ledger['gst']) : 0;
                        $ledger['balance']                = $ledger['gst'];
                        $ledger['chart_id']               = $clearingAc->code;
                        $ledger['client_account_code_id'] = $clearingAc->id;
                        $ledger['narration']              = 'INP_CLEARING';
                        $ledger['gst']                    = 0;
                    } elseif ($type == 2) {
                        $ledger['balance_type']           = 2;
                        $ledger['credit']                 = $ledger['gst'] < 0 ? abs($ledger['gst']) : 0;
                        $ledger['debit']                  = $ledger['gst'] > 0 ? abs($ledger['gst']) : 0;
                        $ledger['balance']                = $ledger['gst'];
                        $ledger['chart_id']               = $payableAc->code;
                        $ledger['client_account_code_id'] = $payableAc->id;
                        $ledger['narration']              = 'INP_PAYABLE';
                        $ledger['gst']                    = 0;
                    }
                    GeneralLedger::create($ledger);
                }
            }

            // Bank Ac Calculation
            $genled = GeneralLedger::where('transaction_id', $tran_id)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('chart_id', 'not like', '99999%')
                ->whereNotIn('narration', ['INP_PAYABLE', 'INP_CLEARING', 'INP_BANK'])->get();
            $genBl = $genled->sum('credit') - $genled->sum('debit');
            if ($genBl < 0) {
                $ledger['balance'] = $genBl;
                $ledger['credit']  = abs($genBl);
                $ledger['debit']   = 0;
            } else {
                $ledger['balance'] = $genBl;
                $ledger['debit']   = abs($genBl);
                $ledger['credit']  = 0;
            }

            $ledger['balance_type']           = 1;
            $ledger['chart_id']               = $bankAccount->code;
            $ledger['client_account_code_id'] = $bankAccount->id;
            $ledger['narration']              = 'INP_BANK';

            $bac = GeneralLedger::where('chart_id', $bankAccount->code)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('source', 'INP')
                ->where('transaction_id', $tran_id)
                ->first();
            if ($bac) {
                $bac->update($ledger);
            } else {
                GeneralLedger::create($ledger);
            }
            //RetailEarning Calculation
            // RetainEarning::retain($client->id, $profession->id, $tran_date, $ledger, ['INP', 'INP']);

            // // Retain Earning For each Transection
            // RetainEarning::tranRetain($client->id, $profession->id, $tran_id, $ledger, ['INP', 'INP']);
            // //RetailEarning Calculation End....
            try {
                activity()
                ->performedOn(new GeneralLedger())
                ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'ADT Data Deleted'])
                ->log('Add/Edit Data > Bank Statement > Input > ' . $client->fullname . ' > ' . $profession->name . ' Input Data ');
                BankStatementInput::where('is_posted', 0)->where('client_id', $client->id)
                    ->where('profession_id', $profession->id)
                    ->update([
                        'is_posted' => 1,
                        'tran_id'   => $tran_id
                ]);
                DB::commit();
                Alert::success('Data Insert', 'Data Successfully Inserted');
            } catch (\Exception $ex) {
                DB::rollBack();
                Alert::error('Sever Side Error!');
            }
        } else {
            toast('Please Add Account Code', 'warning');
        }
    }

    // Update Bank Statement Input
    public function update(Request $request)
    {
        $bankAccount = ClientAccountCode::findOrFail($request->bank_account);
        $client      = Client::findOrFail($request->client_id);
        $profession  = Profession::findOrFail($request->profession_id);
        $gstMethod   = $client->gst_method; // 0=None,1=Cash, 2=Accrued,
        $gstEnabled  = $client->is_gst_enabled; // 1=YES , 0=NO
        $tran_id     = $request->tran_id;


        $bank_statements = BankStatementInput::with('client_account_code')
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('tran_id', $tran_id)
            ->where('is_posted', 1)
            ->where('account_code', '!=', null)
            ->get();

        // Check Period Lock
        if (periodLock($client->id, $bank_statements->first()->date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        $period     = Period::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            // ->where('start_date', '<=', $bank_statements->first()->date)
            ->where('end_date', '>=', $bank_statements->first()->date)->first();

        DB::beginTransaction();
        GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('transaction_id', $tran_id)
            ->where('chart_id', '!=', '999999')
            ->where('source', 'INP')
            ->forceDelete();

        Gsttbl::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('trn_id', $tran_id)
            ->where('source', 'INP')
            ->forceDelete();

        if ($bank_statements->count()) {
            foreach ($bank_statements as $bank_statement) {
                $retain_date = $bank_statement->date;
                $tran_date   = $bank_statement->date->format('Y-m-d');
                $debit       = $bank_statement->debit;
                $credit      = $bank_statement->credit;
                $chart_id    = $bank_statement->client_account_code->code;
                $type        = $bank_statement->client_account_code->type;      // 1=Debit , 2=Credit
                $gst_code    = $bank_statement->client_account_code->gst_code;  // GST,NILL,FREE,CAP,INP

                if ($type == 1) {
                    $debit  = $debit == 0 ? -$credit : $debit;
                } else {
                    $credit = $credit == 0 ? -$debit : $credit;
                }
                // GST Table
                $gst['client_id']          = $client->id;
                $gst['profession_id']      = $profession->id;
                $gst['period_id']          = $period->id;
                $gst['trn_id']             = $tran_id;
                $gst['trn_date']           = $tran_date;
                $gst['chart_code']         = $chart_id;
                $gst['source']             = 'INP';
                $gst['gross_amount']       =
                    $gst['gst_accrued_amount'] =
                    $gst['gst_cash_amount']    =
                    $gst['net_amount']         = 0;

                if ($type == 1) { //Debit
                    $gst['gross_amount']       =
                        $gst['net_amount']         = $debit;
                    if ($gstEnabled == 1) {
                        if ($gstMethod == 1 && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) { //Cash
                            $gst['gst_accrued_amount'] = 0;
                            $gst['gst_cash_amount']    = $debit / 11;
                            $gst['net_amount']         = $debit - ($debit / 11);
                        } elseif ($gstMethod == 2 && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) { //Accrud
                            $gst['gst_accrued_amount'] = $debit / 11;
                            $gst['gst_cash_amount']    = 0;
                            $gst['net_amount']         = $debit - ($debit / 11);
                        } else {
                            $gst['gst_accrued_amount'] = 0;
                            $gst['gst_cash_amount']    = 0;
                        }
                    } else { //gst disabled
                        $gst['gst_accrued_amount'] = 0;
                        $gst['gst_cash_amount']    = 0;
                    }
                } else { //credit
                    $gst['gross_amount'] =
                        $gst['net_amount']   = $credit;

                    if ($gstEnabled == 1) {
                        if ($gstMethod == 1 && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) { //Cash
                            $gst['gst_accrued_amount'] = 0;
                            $gst['gst_cash_amount']    = $credit / 11;
                            $gst['net_amount']         = $credit - ($credit / 11);
                        } elseif ($gstMethod == 2 && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) { //Accrud
                            $gst['gst_accrued_amount'] = $credit / 11;
                            $gst['gst_cash_amount']    = 0;
                            $gst['net_amount']         = $credit - ($credit / 11);
                        } else { //Accrud
                            $gst['gst_accrued_amount'] = 0;
                            $gst['gst_cash_amount']    = 0;
                        }
                    } else { //gst disabled
                        $gst['gst_accrued_amount'] = 0;
                        $gst['gst_cash_amount']    = 0;
                    }
                }
                Gsttbl::create($gst);

                // Ledger Calculation
                $ledger['chart_id']               = $chart_id;
                $ledger['date']                   = $tran_date;
                $ledger['narration']              = $bank_statement->narration;
                $ledger['source']                 = 'INP';
                $ledger['client_id']              = $client->id;
                $ledger['profession_id']          = $profession->id;
                $ledger['transaction_id']         = $tran_id;
                $ledger['client_account_code_id'] = $bank_statement->account_code;
                $ledger['balance']                = $gst['net_amount'];
                $ledger['debit']                  = $ledger['credit'] = 0;
                $ledger['gst']                    = $gst['gst_accrued_amount'] == 0 ? $gst['gst_cash_amount'] : $gst['gst_accrued_amount'];

                // if ($type == 1) {
                //     $ledger['debit']        = $gst['gross_amount'];
                //     $ledger['credit']       = 0;
                //     $ledger['balance_type'] = 1;
                // } elseif ($type == 2) {
                //     $ledger['debit']        = 0;
                //     $ledger['credit']       = $gst['gross_amount'];
                //     $ledger['balance_type'] = 2;
                // }

                if ($type == 1) {
                    $ledger['debit']        = $gst['gross_amount'] > 0 ? abs($gst['gross_amount']) : 0;
                    $ledger['credit']       = $gst['gross_amount'] < 0 ? abs($gst['gross_amount']) : 0;
                    $ledger['balance_type'] = 1;
                } elseif ($type == 2) {
                    $ledger['debit']        = $gst['gross_amount'] < 0 ? abs($gst['gross_amount']) : 0;
                    $ledger['credit']       = $gst['gross_amount'] > 0 ? abs($gst['gross_amount']) : 0;
                    $ledger['balance_type'] = 2;
                }
                GeneralLedger::create($ledger);

                if ($gstEnabled == 1 && ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP')) {
                    // Payable Clearing calculation
                    $payableAc = ClientAccountCode::where('client_id', $client->id)
                        ->where('profession_id', $profession->id)
                        ->where('code', 912100)
                        ->first();
                    $clearingAc = ClientAccountCode::where('client_id', $client->id)
                        ->where('profession_id', $profession->id)
                        ->where('code', 912101)
                        ->first();
                    $ledger['payable_liabilty'] = $chart_id;
                    // if ($type == 1) {
                    //     $ledger['balance_type']           = 1;
                    //     $ledger['debit']                  = $ledger['balance'] = $ledger['gst'];
                    //     $ledger['credit']                 = 0;
                    //     $ledger['chart_id']               = $clearingAc->code;
                    //     $ledger['client_account_code_id'] = $clearingAc->id;
                    //     $ledger['narration']              = 'INP_CLEARING';
                    //     $ledger['gst']                    = 0;
                    // } elseif ($type == 2) {
                    //     $ledger['balance_type']           = 2;
                    //     $ledger['credit']                 = $ledger['balance'] = $ledger['gst'];
                    //     $ledger['debit']                  = 0;
                    //     $ledger['chart_id']               = $payableAc->code;
                    //     $ledger['client_account_code_id'] = $payableAc->id;
                    //     $ledger['narration']              = 'INP_PAYABLE';
                    //     $ledger['gst']                    = 0;
                    // }
                    if ($type == 1) {
                        $ledger['balance_type']           = 1;
                        $ledger['debit']                  = $ledger['gst'] > 0 ? abs($ledger['gst']) : 0;
                        $ledger['credit']                 = $ledger['gst'] < 0 ? abs($ledger['gst']) : 0;
                        $ledger['balance']                = $ledger['gst'];
                        $ledger['chart_id']               = $clearingAc->code;
                        $ledger['client_account_code_id'] = $clearingAc->id;
                        $ledger['narration']              = 'INP_CLEARING';
                        $ledger['gst']                    = 0;
                    } elseif ($type == 2) {
                        $ledger['balance_type']           = 2;
                        $ledger['credit']                 = $ledger['gst'] < 0 ? abs($ledger['gst']) : 0;
                        $ledger['debit']                  = $ledger['gst'] > 0 ? abs($ledger['gst']) : 0;
                        $ledger['balance']                = $ledger['gst'];
                        $ledger['chart_id']               = $payableAc->code;
                        $ledger['client_account_code_id'] = $payableAc->id;
                        $ledger['narration']              = 'INP_PAYABLE';
                        $ledger['gst']                    = 0;
                    }
                    GeneralLedger::create($ledger);
                }
            }

            // Bank Ac Calculation
            $genled = GeneralLedger::where('transaction_id', $tran_id)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('chart_id', 'not like', '99999%')
                ->whereNotIn('narration', ['INP_PAYABLE', 'INP_CLEARING', 'INP_BANK'])->get();
            $genBl = $genled->sum('credit') - $genled->sum('debit');
            if ($genBl < 0) {
                $ledger['balance'] = $genBl;
                $ledger['credit']  = abs($genBl);
                $ledger['debit']   = 0;
            } else {
                $ledger['balance'] = $genBl;
                $ledger['debit']   = abs($genBl);
                $ledger['credit']  = 0;
            }

            $ledger['balance_type']           = 1;
            $ledger['chart_id']               = $bankAccount->code;
            $ledger['client_account_code_id'] = $bankAccount->id;
            $ledger['narration']              = 'INP_BANK';

            $bac = GeneralLedger::where('chart_id', $bankAccount->code)
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('transaction_id', $tran_id)
            ->where('source', 'INP')->first();
            if ($bac) {
                $bac->update($ledger);
            } else {
                GeneralLedger::create($ledger);
            }

            //RetailEarning Calculation
            // RetainEarning::retain($client->id, $profession->id, $retain_date, $ledger, ['INP', 'INP']);

            // Retain Earning For each Transection
            // RetainEarning::tranRetain($client->id, $profession->id, $tran_id, $ledger, ['INP', 'INP']);
            //RetailEarning Calculation End....

            try {
                DB::commit();
                Alert::success('Bank Statement List', 'Transaction Successfully Updated');
            } catch (\Exception $ex) {
                DB::rollBack();
                Alert::error('Sever Side Error!');
            }
        } else {
            toast('Please Add Account Code', 'warning');
        }
    }
}
