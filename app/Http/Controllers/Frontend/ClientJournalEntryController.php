<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Profession;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class ClientJournalEntryController extends Controller
{
    public function profession()
    {
        $client = Client::with('professions')->findOrFail(client()->id);
        return view('frontend.accounts.journal_entry.profession', compact('client'));
    }

    public function input(Client $client, Profession $profession)
    {
        $client_account_codes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', 'not like', '9%')
            ->orderBy('code')
            ->get();
        $inputs = JournalEntry::with('client_account_code')
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('is_posted', 0)
            ->get();
        $liquid_asset_account_codes = ClientAccountCode::where('additional_category_id', aarks('liquid_asset_id'))
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('code', 'asc')
            ->get();
        $journal_number =JournalEntry::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->get();
        return view('frontend.accounts.journal_entry.input', compact('client', 'profession', 'client_account_codes', 'inputs', 'liquid_asset_account_codes', 'journal_number'));
    }
    public function read(Request $r)
    {
        $inputs = JournalEntry::with('client_account_code')
            ->where('client_id', $r->client_id)
            ->where('profession_id', $r->profession_id)
            ->where('is_posted', 0)
            ->where('is_edited', 0)
            ->orderBy('account_code')
            ->get();
        $html = '';
        $html .= '<thead>';
        $html .= '    <tr class="bg-primary text-light">';
        $html .= '        <th style="text-align:center;">A/c Code</th>';
        $html .= '        <th style="text-align:center;">Narration</th>';
        $html .= '        <th style="text-align:center;">Tx Code</th>';
        $html .= '        <th style="text-align:center;">Debit</th>';
        $html .= '        <th style="text-align:center;">Credit</th>';
        $html .= '        <th style="text-align:center;">Excl Tax</th>';
        $html .= '        <th style="text-align:center;">Tax</th>';
        $html .= '        <th style="text-align:center;">Action</th>';
        $html .= '    </tr>';
        $html .= '</thead>';

        if ($inputs->count() > 0) {
            $totalD  = $totalC =$totalB =$totalG = 0;
            foreach ($inputs as $input) {
                $html .= '<tr id="input_row_' . $input->id . '">';
                $html .= '<td>' . $input->client_account_code->name  . '</td>';
                $html .= '<td>' . $input->narration . '</td>';
                $html .= '<td>' . $input->gst_code . '</td>';

                $Idebit   = $input->debit;
                $Icredit  = $input->credit;

                if ($input->credit < 0) {
                    $Idebit = $Icredit;
                    $Icredit = 0;
                }
                if ($input->debit < 0) {
                    $Icredit = $Idebit;
                    $Idebit = 0;
                }

                $totalD += abs($Idebit);
                $totalC += abs($Icredit);
                $totalG += abs($input->gst);
                $totalB += abs($input->net_amount);

                $html .= '<td style="text-align:right">' . number_format(abs($Idebit), 2) . '</td>';
                $html .= '<td style="text-align:right">' . number_format(abs($Icredit), 2) . '</td>';

                $html .= '<td style="text-align:right">' . number_format(abs($input->net_amount), 2) . '</td>';
                $html .= '<td style="text-align:right">' . number_format(abs($input->gst), 2) . '</td>';
                $html .= '<td style="text-align:center">';
                $html .= '<a data-id="' . $input->id . '" class="text-danger" id="delete" href="' . route('client.je_delete', $input->id) . '"><i class="ace-icon fa fa-trash bigger-130"></i></a>';
                $html .= '</td>';
                $html .= '</tr>';
            }
            $html .= '<tfooter>';
            $html .= '<tr>';
            $html .=    '<td  style="text-align: right" colspan="3"><b>Total:</b></td>';
            $html .=    '<td style="text-align:right"><b>' . number_format(abs($totalD), 2) . '</b></td>';
            $html .=    '<td style="text-align:right"><b>' . number_format(abs($totalC), 2) . '</b></td>';
            $html .=    '<td style="text-align:right"><b>' . number_format($totalB, 2) . '</b></td>';
            $html .=    '<td style="text-align:right"><b>' . number_format($totalG, 2) . '</b></td>';
            $html .=    '<td></td>';
            $html .= '</tr>';
            $html .= '</tfooter>';
            return json_encode(['status' => 'success', 'html' => $html, 'totalDebit' => $totalD, 'totalCredit' => $totalC]);
        } else {
            $html .= '<tr colspan="">';
            $html .= '<td>SORRY NO DATA FOUND!</td>';
            $html .= '<tr>';
            return json_encode(['status' => 'danger', 'html' => $html]);
        }
    }
    public function store(Request $request)
    {
        $data = [
            'account_code'   => $request->account_code,
            'client_id'      => $request->client_id,
            'profession_id'  => $request->profession_id,
            'gst_code'       => $request->gst_code,
            'narration'      => $request->narration,
            'is_edited'      => $request->is_edited??0,
            'tran_id'        => $request->tran_id??null,
            'journal_number' => $request->journal_number??null,
            'date'           => $request->date??null,
        ];
        if ($request->type == 1) {
            if ($request->debit == '') {
                $data['debit']      = - $request->credit;
                $data['gst']        = - $request->gst;
                $data['net_amount'] = - $request->net_amount;
            } else {
                $data['gst']        = $request->gst;
                $data['debit']      = $request->debit;
                $data['net_amount'] = $request->net_amount;
            }
        } else {
            if ($request->credit == '') {
                $data['credit']     = - $request->debit;
                $data['gst']        = - $request->gst;
                $data['net_amount'] = - $request->net_amount;
            } else {
                $data['credit']     = $request->credit;
                $data['gst']        = $request->gst;
                $data['net_amount'] = $request->net_amount;
            }
        }
        $journal_entry = JournalEntry::create($data);
        $journal_entry->load('client_account_code');
        return response()->json($journal_entry, 200);
    }
    public function delete(JournalEntry $journal)
    {
        $journal->delete();
        return back();
    }

    public function post(Request $request)
    {
        $this->validate($request, [
            'journal_date'      => 'required',
            'journal_reference' => 'required',
            'journal_number'    => 'required',
        ]);
        $client     = Client::findOrFail($request->client_id);
        $profession = Profession::findOrFail($request->profession_id);

        $clientId     = $client->id;
        $professionId = $profession->id;
        $periodId     = 0;
        $gstMethod    = $client->gst_method;       // 0=None,2=Accrued,1=Cash
        $gstEnabled   = $client->is_gst_enabled;   // 1=YES , 0=NO
        $date         = makeBackendCompatibleDate($request->journal_date);
        $trnDate      = $date->format('dmY').rand(11, 99);

        $ledger['batch']               = $request->journal_number;
        $gst    ['client_id']          = $clientId;
        $gst    ['profession_id']      = $professionId;
        $gst    ['period_id']          = $periodId;
        $gst    ['trn_id']             = $periodId;
        $gst    ['trn_date']           = $bsd = $date;
        $gst    ['chart_code']         = 000000;
        $gst    ['source']             = "JNP";
        $gst    ['gross_amount']       = 0;
        $gst    ['gst_accrued_amount'] = 0;
        $gst    ['gst_cash_amount']    = 0;
        $gst    ['net_amount']         = 0;

        $journals = JournalEntry::with('client_account_code')
            ->where('client_id', $clientId)
            ->where('profession_id', $professionId)
            ->where('is_posted', 0)
            ->get();

        $journalCheck = $journals->first();
        $period       = Period::where('client_id', $clientId)
                ->where('profession_id', $professionId)
                // ->where('start_date', '<=', $bsd)
                ->where('end_date', '>=', $bsd)->first();

        if (periodLock($clientId, $date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }

        if ($period) {
            if ($journalCheck) {
                foreach ($journals as $journal) {
                    $gst['period_id']  = $periodId = $period->id;
                    $gst['trn_id']     = $tranId   = $clientId . $periodId . $trnDate;
                    $gst['chart_code'] = $chart_id = $journal->client_account_code->code;
                    $type         = $journal->client_account_code->type;
                    $gst_code     = $journal->gst_code;
                    $c_Id         = intval($chart_id / 100000);

                    if (($c_Id == '1' || $c_Id == '2' || $c_Id == '5')) {
                        if ($type == 1) {
                            if (($gstEnabled == 0) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                                $gst['gross_amount'] = $journal->debit;
                                $gst['net_amount']   = $journal->net_amount;
                            } elseif ($gstMethod == 2) {
                                $gst['gross_amount']       = $journal->debit;
                                $gst['gst_accrued_amount'] = $journal->gst;
                                $gst['net_amount']         = $journal->net_amount;
                            } elseif ($gstMethod == 1) {
                                $gst['gross_amount']    = $journal->debit;
                                $gst['gst_cash_amount'] = $journal->gst;
                                $gst['net_amount']      = $journal->net_amount;
                            }
                        } elseif ($type == 2) {
                            if (($gstEnabled == 0) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                                $gst['gross_amount'] = $journal->credit;
                                $gst['net_amount']   = $journal->net_amount;
                            } elseif ($gstMethod == 1) {
                                $gst['gross_amount']    = $journal->credit;
                                $gst['gst_cash_amount'] = $journal->gst;
                                $gst['net_amount']      = $journal->net_amount;
                            } elseif ($gstMethod == 2) {
                                $gst['gross_amount']       = $journal->credit;
                                $gst['gst_accrued_amount'] = $journal->gst;
                                $gst['net_amount']         = $journal->net_amount;
                            }
                        }
                        $gstTblData = Gsttbl::where('trn_id', $tranId)->where('source', 'JNP')->where('chart_code', $chart_id)->first();
                        if ($gstTblData != null) {
                            $gst['gross_amount'] = $gst['gross_amount'] + $gstTblData->gross_amount;
                            $gst['gst_accrued_amount'] = $gst['gst_accrued_amount'] + $gstTblData->gst_accrued_amount;
                            $gst['gst_cash_amount'] = $gst['gst_cash_amount'] + $gstTblData->gst_cash_amount;
                            $gst['net_amount'] = $gst['net_amount'] + $gstTblData->net_amount;

                            Gsttbl::where('trn_id', $tranId)
                                ->where('chart_code', $chart_id)
                                ->where('source', 'JNP')
                                ->update($gst);
                        } else {
                            Gsttbl::create($gst);
                        }
                        // Ledger Calculations

                        $ledger['chart_id']               = $chart_id;
                        $ledger['date']                   = $gst['trn_date'];
                        $ledger['narration']              = $request->journal_reference;
                        $ledger['source']                 = 'JNP';
                        $ledger['client_id']              = $clientId;
                        $ledger['profession_id']          = $professionId;
                        $ledger['transaction_id']         = $tranId;
                        $ledger['client_account_code_id'] = $journal->account_code;
                        $ledger['balance']                = $gst['net_amount'];
                        $ledger['debit']  = $ledger['credit'] = 0;
                        $ledger['gst'] = $gst['gst_accrued_amount'] == ''? $gst['gst_cash_amount']: $gst['gst_accrued_amount'];

                        if ($type == 1) {
                            $ledger['debit']        = $gst['gross_amount'];
                            $ledger['credit']       = 0;
                            $ledger['balance_type'] = 1;
                        } elseif ($type == 2) {
                            $ledger['debit']        = 0;
                            $ledger['credit']       = $gst['gross_amount'];
                            $ledger['balance_type'] = 2;
                        }


                        // Leadger Data Calculation
                        $ledgerData = GeneralLedger::where('transaction_id', $tranId)
                            ->where('chart_id', $chart_id)
                            ->where('source', 'JNP')
                            ->first();
                        if ($ledgerData != null) {
                            $ledgerData->update($ledger);
                        } else {
                            GeneralLedger::create($ledger);
                        }


                        // Payable Clearing calculation


                        $payableAc = ClientAccountCode::where('client_id', $clientId)
                            ->where('profession_id', $professionId)
                            ->where('code', 912100)
                            ->first();
                        $clearingAc = ClientAccountCode::where('client_id', $clientId)
                            ->where('profession_id', $professionId)
                            ->where('code', 912101)
                            ->first();
                        $ledger['payable_liabilty'] = $chart_id;
                        if ($type == 1) {
                            $ledger['balance_type']           = 1;
                            $ledger['debit']                  = $ledger['balance']      = $ledger['gst'];
                            $ledger['credit']                 = 0;
                            $ledger['chart_id']               = $clearingAc->code;
                            $ledger['client_account_code_id'] = $clearingAc->id;
                            $ledger['narration']              = 'JNP Clearing';
                            $ledger['gst']                    = 0;
                        } elseif ($type == 2) {
                            $ledger['balance_type']           = 2;
                            $ledger['credit']                 = $ledger['balance']      = $ledger['gst'];
                            $ledger['debit']                  = 0;
                            $ledger['chart_id']               = $payableAc->code;
                            $ledger['client_account_code_id'] = $payableAc->id;
                            $ledger['narration']              = 'JNP Payable';
                            $ledger['gst']                    = 0;
                        }

                        $payableData = GeneralLedger::where('transaction_id', $tranId)
                            ->where('chart_id', $ledger['chart_id'])
                            ->where('source', 'JNP')
                            ->where('payable_liabilty', $chart_id)
                            ->first();
                        if ($payableData != null) {
                            if ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP') {
                                $payableData->update($ledger);
                            }
                        } else {
                            if ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP') {
                                GeneralLedger::create($ledger);
                            }
                        }
                        JournalEntry::where('is_posted', 0)->where('client_id', $clientId)
                            ->where('profession_id', $professionId)
                            ->update([
                                'is_posted'      => 1,
                                'journal_number' => $request->journal_number,
                                'date'           => $date,
                                'tran_id'        => $tranId
                            ]);
                    }
                }


                // Retain Earning For each Transection

                $tranRetainEar = ClientAccountCode::where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->where('code', 999998)
                    ->first();

                $periodStartDate = $date->format('Y-m-') . '01';
                $periodEndDate   = $date->format('Y-m-') . date('t', strtotime($date));

                $inTranRetain = GeneralLedger::where('chart_id', 'LIKE', '1%')
                    ->where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->where('transaction_id', $tranId)
                    ->get();
                $inTranRetainData = $inTranRetain->sum('balance');

                $exTranRetain = GeneralLedger::where('chart_id', 'LIKE', '2%')
                    ->where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->where('transaction_id', $tranId)
                    ->get();
                $exTranRetainData = $exTranRetain->sum('balance');

                $tranRetainData = $inTranRetainData - $exTranRetainData;

                $ledger['client_account_code_id'] =                   //$retainEar->id;
                    $ledger['gst']                    = 0;
                $ledger['balance']                = $tranRetainData;
                if ($inTranRetainData > $exTranRetainData) {
                    $ledger['credit'] = abs($tranRetainData);
                    $ledger['debit']  = 0;
                } else {
                    $ledger['debit']  = abs($tranRetainData);
                    $ledger['credit'] = 0;
                }
                $isTranRetainEar = GeneralLedger::where('transaction_id', $tranId)
                    ->where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->where('chart_id', $tranRetainEar->code)
                    ->where('source', 'JNP')
                    ->first();
                $ledger['chart_id']  = $tranRetainEar->code;
                $ledger['narration'] = "JNP Tran Retain Data";


                if ($isTranRetainEar != null) {
                    $isTranRetainEar->update($ledger);
                } else {
                    GeneralLedger::create($ledger);
                }
                // RetailEarning Calculation End

                // Retian Earning Calculation
                $retainEar = ClientAccountCode::where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->where('code', 999999)
                    ->first();

                if ($date->format('m') >= 07 & $date->format('m') <= 12) {
                    $start_year = $date->format('Y') . '-07-01'; //2020-07-01==2021-06-30
                    $end_year   = $date->format('Y') + 1 . '-06-30';
                } else {
                    $start_year = $date->format('Y') - 1 . '-07-01'; //2019-07-01==2020-06-30
                    $end_year   = $date->format('Y') . '-06-30';
                }


                $inRetain = GeneralLedger::where('date', '>=', $start_year)
                    ->where('date', '<=', $end_year)
                    ->where('chart_id', 'LIKE', '1%')
                    ->where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->get();
                $inRetainData = $inRetain->sum('balance');

                $exRetain = GeneralLedger::where('date', '>=', $start_year)
                    ->where('date', '<=', $end_year)
                    ->where('chart_id', 'LIKE', '2%')
                    ->where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->get();
                $exRetainData = $exRetain->sum('balance');

                $retainData               = $inRetainData - $exRetainData;
                $ledger['chart_id']               = $retainEar->code;
                $ledger['client_account_code_id'] =                                 //$retainEar->id;
                    $ledger['gst']                    = 0;
                $ledger['balance']                = $retainData;
                $ledger['date']                = $start_year;
                $ledger['narration']              = "JNP Retain Data";
                if ($inRetainData > $exRetainData) {
                    $ledger['credit'] = abs($retainData);
                    $ledger['debit']  = 0;
                } else {
                    $ledger['debit']  = abs($retainData);
                    $ledger['credit'] = 0;
                }
                $isRetain = GeneralLedger::where('date', '>=', $start_year)
                    ->where('date', '<=', $end_year)
                    ->where('chart_id', $retainEar->code)
                    ->where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->where('source', 'JNP')->first();
                if ($isRetain != null) {
                    $isRetain->update($ledger);
                } else {
                    GeneralLedger::create($ledger);
                }
                try {
                    Alert::success('Journal Post Success');
                } catch (\Exception $e) {
                    return $e;
                    Alert::error('Journal Post Not Success');
                }
            } else {
                Alert::warning('Journal Data not found!');
            }
        } else {
            Alert::warning('Select a valid date which belongsto period date');
        }
        return back();
    }














    //=====================
    //Journal Entry List
    //=====================
    public function listProfession()
    {
        $client = Client::with('professions')->findOrFail(client()->id);
        return view('frontend.accounts.journal_entry.list.profession', compact('client'));
    }
    public function trans(Client $client, Profession $profession)
    {
        $journals = JournalEntry::where('client_id', $client->id)
        ->where('profession_id', $profession->id)
        ->where('is_posted', 1)
        ->orderBy('journal_number')
        ->get();
        if ($journals->count() <= 0) {
            Alert::error('No Data Found!');
            return back();
        }
        return view('frontend.accounts.journal_entry.list.tran-list', compact('client', 'profession', 'journals'));
    }
    public function transShow(Client $client, Profession $profession, $tran)
    {
        $journals = JournalEntry::with('client_account_code')
        ->where('client_id', $client->id)
        ->where('profession_id', $profession->id)
        ->where('tran_id', $tran)
        ->get();
        if ($journals->count() <= 0) {
            Alert::error('No Data Found!');
            return back();
        }
        return view('frontend.accounts.journal_entry.list.tran-show', compact('client', 'profession', 'journals'));
    }

    public function listEdit(JournalEntry $journal)
    {
        $journals = JournalEntry::with('client_account_code')->where('client_id', $journal->client_id)
        ->where('profession_id', $journal->profession_id)
        ->where('tran_id', $journal->tran_id)
        ->where('journal_number', $journal->journal_number)->get();
        $client = Client::findOrFail($journal->client_id);
        $profession = Profession::findOrFail($journal->profession_id);

        $client_account_codes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', 'not like', '9%')
            ->orderBy('code')
            ->get();

        return view('frontend.accounts.journal_entry.list.edit', compact('client', 'profession', 'journals', 'journal', 'client_account_codes'));
    }
    public function listDelete(JournalEntry $journal)
    {
        Gsttbl::where('client_id', $journal->client_id)
        ->where('source', 'JNP')
        ->where('trn_id', $journal->tran_id)->delete();

        GeneralLedger::where('client_id', $journal->client_id)
        ->where('profession_id', $journal->profession_id)
        ->where('source', 'JNP')
        ->where('transaction_id', $journal->tran_id)->delete();

        JournalEntry::where('client_id', $journal->client_id)
        ->where('profession_id', $journal->profession_id)
        ->where('tran_id', $journal->tran_id)
        ->where('is_posted', 1)->delete();
        toast('Delete Successful!', 'success');
        return redirect()->back();
    }

    public function listUpdate(Request $request)
    {
        $this->validate($request, [
            'date'           => 'required',
            'client_id'      => 'required',
            'profession_id'  => 'required',
            'journal_number' => 'required',
            'account_code'   => 'required|array',
            'id'             => 'required|array',
            'narration'      => 'required|array',
            'type'           => 'required|array',
            'gst_code'       => 'required|array',
            'debit'          => 'required|array',
            'credit'         => 'required|array',
            'gst'            => 'required|array',
            'net_amount'     => 'required|array',
        ]);
        $client       = Client::findOrFail($request->client_id);
        $profession   = Profession::findOrFail($request->profession_id);
        $clientId     = $client->id;
        $professionId = $profession->id;
        $periodId     = 0;
        $gstMethod    = $client->gst_method;                              // 0=None,2=Accrued,1=Cash
        $gstEnabled   = $client->is_gst_enabled;                          // 1=YES , 0=NO
        $date         = makeBackendCompatibleDate($request->date);
        $trnDate      = $date->format('dmY').rand(11, 99);

        $ledger['batch']               = $request->journal_number;
        $gst    ['client_id']          = $clientId;
        $gst    ['profession_id']      = $professionId;
        $gst    ['period_id']          = $periodId;
        $gst    ['trn_id']             = $periodId;
        $gst    ['trn_date']           = $bsd = $date;
        $gst    ['chart_code']         = 000000;
        $gst    ['source']             = "JNP";
        $gst    ['gross_amount']       = 0;
        $gst    ['gst_accrued_amount'] = 0;
        $gst    ['gst_cash_amount']    = 0;
        $gst    ['net_amount']         = 0;

        $period = Period::where('client_id', $clientId)
                ->where('profession_id', $professionId)
                // ->where('start_date', '<=', $bsd)
                ->where('end_date', '>=', $bsd)->first();

        if (periodLock($clientId, $date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        DB::beginTransaction();

        GeneralLedger::where('transaction_id', $request->tran_id)->where('source', 'JNP')->where('client_id', $clientId)->where('profession_id', $professionId)->delete();
        Gsttbl::where('trn_id', $request->tran_id)->where('source', 'JNP')->where('client_id', $clientId)->delete();

        foreach ($request->id as $i => $id) {
            $gst['period_id']  = $periodId = $period->id;
            $gst['trn_id']     = $tranId   = $request->tran_id;
            $gst['chart_code'] = $chart_id = $request->account_code[$i];

            $type         = $request->type[$i];
            $gst_code     = $request->gst_code[$i];
            $c_Id         = intval($chart_id / 100000);
            $data = [
                "account_code"   => $request->code_id[$i],
                "journal_number" => $request->journal_number,
                "tran_id"        => $tranId,
                "date"           => $date,
                "narration"      => $request->narration[$i],
                "debit"          => $request->debit[$i],
                "credit"         => $request->credit[$i],
                "gst"            => $request->gst[$i],
                "net_amount"     => $request->net_amount[$i],
                "is_posted"      => 1,
                "is_edited"      => 0,
                "client_id"      => $clientId,
                "profession_id"  => $professionId,
                "gst_code"       => $gst_code,
            ];
            if ($type == 1) {
                if ($request->debit[$i] == 0) {
                    $data['debit']      = - $request->credit[$i];
                    $data['gst']        = - $request->gst[$i];
                    $data['net_amount'] = - $request->net_amount[$i];
                    $data['credit']     = null;
                }
            }
            if ($type == 2) {
                if ($request->credit[$i] == 0) {
                    $data['credit']     = - $request->debit[$i];
                    $data['gst']        = - $request->gst[$i];
                    $data['net_amount'] = - $request->net_amount[$i];
                    $data['debit']      = null;
                }
            }

            $journal = JournalEntry::updateOrCreate([
                'id'=>$id
            ], $data);



            if (($c_Id == '1' || $c_Id == '2' || $c_Id == '5')) {
                if ($type == 1) {
                    if (($gstEnabled == 0) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                        $gst['gross_amount'] = $journal->debit;
                        $gst['net_amount']   = $journal->net_amount;
                    } elseif ($gstMethod == 2) {
                        $gst['gross_amount']       = $journal->debit;
                        $gst['gst_accrued_amount'] = $journal->gst;
                        $gst['net_amount']         = $journal->net_amount;
                    } elseif ($gstMethod == 1) {
                        $gst['gross_amount']    = $journal->debit;
                        $gst['gst_cash_amount'] = $journal->gst;
                        $gst['net_amount']      = $journal->net_amount;
                    }
                } elseif ($type == 2) {
                    if (($gstEnabled == 0) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                        $gst['gross_amount'] = $journal->credit;
                        $gst['net_amount']   = $journal->net_amount;
                    } elseif ($gstMethod == 1) {
                        $gst['gross_amount']    = $journal->credit;
                        $gst['gst_cash_amount'] = $journal->gst;
                        $gst['net_amount']      = $journal->net_amount;
                    } elseif ($gstMethod == 2) {
                        $gst['gross_amount']       = $journal->credit;
                        $gst['gst_accrued_amount'] = $journal->gst;
                        $gst['net_amount']         = $journal->net_amount;
                    }
                }

                $gstTblData = Gsttbl::where('trn_id', $tranId)->where('source', 'JNP')->where('chart_code', $chart_id)->where('client_id', $clientId)->first();
                if ($gstTblData != null) {
                    $gst['gross_amount'] = $gst['gross_amount'] + $gstTblData->gross_amount;
                    $gst['gst_accrued_amount'] = $gst['gst_accrued_amount'] + $gstTblData->gst_accrued_amount;
                    $gst['gst_cash_amount'] = $gst['gst_cash_amount'] + $gstTblData->gst_cash_amount;
                    $gst['net_amount'] = $gst['net_amount'] + $gstTblData->net_amount;

                    $gstTblData->update($gst);
                } else {
                    Gsttbl::create($gst);
                }
                // Ledger Calculations

                $ledger['chart_id']               = $chart_id;
                $ledger['date']                   = $date;
                $ledger['narration']              = $journal->narration;
                $ledger['source']                 = 'JNP';
                $ledger['client_id']              = $clientId;
                $ledger['profession_id']          = $professionId;
                $ledger['transaction_id']         = $tranId;
                $ledger['client_account_code_id'] = $journal->account_code;
                $ledger['balance']                = $gst['net_amount'];
                $ledger['debit']                  = $ledger['credit'] = 0;

                $ledger['gst'] = $gst['gst_accrued_amount'] == ''? $gst['gst_cash_amount']: $gst['gst_accrued_amount'];

                if ($type == 1) {
                    $ledger['debit']        = $gst['gross_amount'];
                    $ledger['credit']       = 0;
                    $ledger['balance_type'] = 1;
                } elseif ($type == 2) {
                    $ledger['debit']        = 0;
                    $ledger['credit']       = $gst['gross_amount'];
                    $ledger['balance_type'] = 2;
                }


                // Leadger Data Calculation
                $ledgerData = GeneralLedger::where('transaction_id', $tranId)
                            ->where('chart_id', $chart_id)
                            ->where('source', 'JNP')
                            ->where('client_id', $clientId)
                            ->where('profession_id', $professionId)
                            ->first();
                if ($ledgerData != null) {
                    $ledgerData->update($ledger);
                } else {
                    GeneralLedger::create($ledger);
                }


                // Payable Clearing calculation


                $payableAc = ClientAccountCode::where('client_id', $clientId)
                            ->where('profession_id', $professionId)
                            ->where('code', 912100)
                            ->first();
                $clearingAc = ClientAccountCode::where('client_id', $clientId)
                            ->where('profession_id', $professionId)
                            ->where('code', 912101)
                            ->first();
                $ledger['payable_liabilty'] = $chart_id;
                if ($type == 1) {
                    $ledger['balance_type']           = 1;
                    $ledger['debit']                  = $ledger['balance']      = $ledger['gst'];
                    $ledger['credit']                 = 0;
                    $ledger['chart_id']               = $clearingAc->code;
                    $ledger['client_account_code_id'] = $clearingAc->id;
                    $ledger['narration']              = 'JNP Clearing';
                    $ledger['gst']                    = 0;
                } elseif ($type == 2) {
                    $ledger['balance_type']           = 2;
                    $ledger['credit']                 = $ledger['balance']      = $ledger['gst'];
                    $ledger['debit']                  = 0;
                    $ledger['chart_id']               = $payableAc->code;
                    $ledger['client_account_code_id'] = $payableAc->id;
                    $ledger['narration']              = 'JNP Payable';
                    $ledger['gst']                    = 0;
                }

                $payableData = GeneralLedger::where('transaction_id', $tranId)
                            ->where('chart_id', $ledger['chart_id'])
                            ->where('source', 'JNP')
                            ->where('payable_liabilty', $chart_id)
                            ->first();
                if ($payableData != null) {
                    if ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP') {
                        $payableData->update($ledger);
                    }
                } else {
                    if ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP') {
                        GeneralLedger::create($ledger);
                    }
                }
            }
        }


        // Retain Earning For each Transection

        $tranRetainEar = ClientAccountCode::where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->where('code', 999998)
                    ->first();

        $periodStartDate = $date->format('Y-m-') . '01';
        $periodEndDate   = $date->format('Y-m-') . date('t', strtotime($date));

        $inTranRetain = GeneralLedger::where('date', '>=', $periodStartDate)
                    ->where('date', '<=', $periodEndDate)
                    ->where('chart_id', 'LIKE', '1%')
                    ->where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->where('transaction_id', $tranId)
                    ->get();
        $inTranRetainData = $inTranRetain->sum('balance');

        $exTranRetain = GeneralLedger::where('date', '>=', $periodStartDate)
                    ->where('date', '<=', $periodEndDate)
                    ->where('chart_id', 'LIKE', '2%')
                    ->where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->where('transaction_id', $tranId)
                    ->get();
        $exTranRetainData = $exTranRetain->sum('balance');

        $tranRetainData = $inTranRetainData - $exTranRetainData;

        $ledger['client_account_code_id'] =                   //$retainEar->id;
                    $ledger['gst']                    = 0;
        $ledger['balance']                = $tranRetainData;
        if ($inTranRetainData > $exTranRetainData) {
            $ledger['credit'] = abs($tranRetainData);
            $ledger['debit']  = 0;
        } else {
            $ledger['debit']  = abs($tranRetainData);
            $ledger['credit'] = 0;
        }
        $isTranRetainEar = GeneralLedger::where('transaction_id', $tranId)
                    ->where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->where('chart_id', $tranRetainEar->code)
                    ->where('source', 'JNP')
                    ->first();
        $ledger['chart_id']  = $tranRetainEar->code;
        $ledger['narration'] = "JNP Tran Retain Data";


        if ($isTranRetainEar != null) {
            $isTranRetainEar->update($ledger);
        } else {
            GeneralLedger::create($ledger);
        }
        // RetailEarning Calculation End

        // Retian Earning Calculation
        $retainEar = ClientAccountCode::where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->where('code', 999999)
                    ->first();

        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $start_year = $date->format('Y') . '-07-01'; //2020-07-01==2021-06-30
            $end_year   = $date->format('Y') + 1 . '-06-30';
        } else {
            $start_year = $date->format('Y') - 1 . '-07-01'; //2019-07-01==2020-06-30
            $end_year   = $date->format('Y') . '-06-30';
        }


        $inRetain = GeneralLedger::where('date', '>=', $start_year)
                    ->where('date', '<=', $end_year)
                    ->where('chart_id', 'LIKE', '1%')
                    ->where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->get();
        $inRetainData = $inRetain->sum('balance');

        $exRetain = GeneralLedger::where('date', '>=', $start_year)
                    ->where('date', '<=', $end_year)
                    ->where('chart_id', 'LIKE', '2%')
                    ->where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->get();
        $exRetainData = $exRetain->sum('balance');

        $retainData               = $inRetainData - $exRetainData;
        $ledger['chart_id']               = $retainEar->code;
        $ledger['client_account_code_id'] =                                 //$retainEar->id;
                    $ledger['gst']                    = 0;
        $ledger['balance']                = $retainData;
        $ledger['date']                = $start_year;
        $ledger['narration']              = "JNP Retain Data";
        if ($inRetainData > $exRetainData) {
            $ledger['credit'] = abs($retainData);
            $ledger['debit']  = 0;
        } else {
            $ledger['debit']  = abs($retainData);
            $ledger['credit'] = 0;
        }
        $isRetain = GeneralLedger::where('date', '>=', $start_year)
                    ->where('date', '<=', $end_year)
                    ->where('chart_id', $retainEar->code)
                    ->where('client_id', $clientId)
                    ->where('profession_id', $professionId)
                    ->where('source', 'JNP')->first();
        if ($isRetain != null) {
            $isRetain->update($ledger);
        } else {
            GeneralLedger::create($ledger);
        }

        try {
            DB::commit();
            Alert::success('Journal Update Success');
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
            Alert::error('Journal Update Not Success');
        }
        return back();
    }
}
