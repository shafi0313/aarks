<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Profession;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Actions\RetainEarning;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class JournalEntryController extends Controller
{
    public function client()
    {
        if ($error = $this->sendPermissionError('admin.journal_entry.index')) {
            return $error;
        }

        $clients = Client::all();
        return view('admin.journal_entry.client', compact('clients'));
    }
    public function profession($id)
    {
        if ($error = $this->sendPermissionError('admin.journal_entry.index')) {
            return $error;
        }
        $client = Client::with('professions')->findOrFail($id);
        return view('admin.journal_entry.profession', compact('client'));
    }

    public function input(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.journal_entry.create')) {
            return $error;
        }
        $client_account_codes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', 'not like', '99999%')
            ->orderBy('code')
            ->get();
        $inputs = JournalEntry::with('client_account_code')
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('is_posted', 0)
            ->get();
        $journal_number = JournalEntry::where('client_id', $client->id)
            ->where('profession_id', $profession->id)->max('journal_number') + 1;
        return view('admin.journal_entry.input', compact('client', 'profession', 'client_account_codes', 'inputs', 'journal_number'));
    }
    public function number(Request $r)
    {
        $journal_number = JournalEntry::where('client_id', $r->client_id)
            ->where('profession_id', $r->profession_id)->max('journal_number') + 1;
        return response()->json(['status' => 'success', 'count' => $journal_number]);

        $date   = makeBackendCompatibleDate($r->date);
        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $from = $date->format('Y') . '-07-01'; //2020-07-01==2021-06-30
            $to   = $date->format('Y') + 1 . '-06-30';
        } else {
            $from = $date->format('Y') - 1 . '-07-01'; //2019-07-01==2020-06-30
            $to   = $date->format('Y') . '-06-30';
        }

        $ledgerBatch = GeneralLedger::select(DB::raw('max(batch) as batch'))
            ->where('source', 'JNP')
            ->where('client_id', $r->client_id)
            ->where('profession_id', $r->profession_id)
            ->whereBetween('date', [$from, $to])
            ->groupBy('batch')
            ->get();
        return response()->json(['status' => 'success', 'count' => $ledgerBatch->max('batch'), 'data' => $ledgerBatch]);
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
        if (auth()->user()->can('admin.journal_entry.edit') || auth()->user()->can('admin.journal_entry.delete')) {
            $html .= '        <th style="text-align:center;">Action</th>';
        }
        $html .= '    </tr>';
        $html .= '</thead>';

        if ($inputs->count() > 0) {
            $totalD  = $totalC = $totalB = $totalG = 0;
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
                $totalG += abs($input->gst); // Excl Tax
                $totalB += abs($input->net_amount); // Tax

                $html .= '<td style="text-align:right">' . number_format(abs($Idebit), 2) . '</td>';
                $html .= '<td style="text-align:right">' . number_format(abs($Icredit), 2) . '</td>';

                $html .= '<td style="text-align:right">' . number_format(abs($input->net_amount), 2) . '</td>';
                $html .= '<td style="text-align:right">' . number_format(abs($input->gst), 2) . '</td>';
                if (auth()->user()->can('admin.journal_entry.edit') || auth()->user()->can('admin.journal_entry.delete')) {
                    $html .= '<td style="text-align:center">';
                    $html .= '<a data-id="' . $input->id . '" class="red" id="delete" href="' . route('journal_entry.delete', $input->id) . '"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
                    $html .= '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '<tfooter>';
            $html .= '<tr>';
            $html .=    '<td  style="text-align: right" colspan="3"><b>Total:</b></td>';
            $html .=    '<td style="text-align:right"><b>' . number_format(abs($totalD), 2) . '</b></td>';
            $html .=    '<td style="text-align:right"><b>' . number_format(abs($totalC), 2) . '</b></td>';
            $html .=    '<td style="text-align:right"></td>';
            $html .=    '<td style="text-align:right"></td>';
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
        // return $request;
        if ($error = $this->sendPermissionError('admin.journal_entry.create')) {
            return $error;
        }
        // if (periodLock($request->client_id, makeBackendCompatibleDate($request->date))) {
        //     Alert::error('Your enter data period is locked, check administration');
        //     return back();
        // }
        $data = [
            'account_code'   => $request->account_code,
            'client_id'      => $request->client_id,
            'profession_id'  => $request->profession_id,
            'gst_code'       => $request->gst_code,
            'narration'      => $request->narration,
            'is_edited'      => $request->is_edited ?? 0,
            'tran_id'        => $request->tran_id ?? null,
            'journal_number' => $request->journal_number ?? null,
            'date'           => $request->date ?? null,
        ];
        if ($request->type == 1) {
            if ($request->debit == '') {
                $data['debit']      = -$request->credit;
                $data['gst']        = -$request->gst;
                $data['net_amount'] = -$request->net_amount;
            } else {
                $data['gst']        = $request->gst;
                $data['debit']      = $request->debit;
                $data['net_amount'] = $request->net_amount;
            }
        } else {
            if ($request->credit == '') {
                $data['credit']     = -$request->debit;
                $data['gst']        = -$request->gst;
                $data['net_amount'] = -$request->net_amount;
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
        if ($error = $this->sendPermissionError('admin.journal_entry.delete')) {
            return $error;
        }
        if ($journal->date && periodLock($journal->client_id, $journal->date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        $journal->forceDelete();
        return back();
    }

    public function post(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.journal_entry.create')) {
            return $error;
        }

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

        $ledger['batch'] = $journal_number = (int) str_replace('#', '', $request->journal_number);

        $gst['client_id']          = $clientId;
        $gst['profession_id']      = $professionId;
        $gst['period_id']          = $periodId;
        $gst['trn_id']             = $periodId;
        $gst['trn_date']           = $bsd = $date;
        $gst['chart_code']         = 0;
        $gst['source']             = "JNP";
        $gst['gross_amount']       = 0;
        $gst['gst_accrued_amount'] = 0;
        $gst['gst_cash_amount']    = 0;
        $gst['net_amount']         = 0;


        $journals = JournalEntry::with('client_account_code')
            ->where('client_id', $clientId)
            ->where('profession_id', $professionId)
            ->where('is_posted', 0)
            ->get();
        $journalCheck = $journals->first();
        $period       = Period::where('client_id', $clientId)
            ->where('profession_id', $professionId)
            // ->where('start_date', '<=', $bsd->format('Y-m-d'))
            ->where('end_date', '>=', $bsd->format('Y-m-d'))->first();

        if (periodLock($clientId, $date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        DB::beginTransaction();
        if ($period) {
            if ($journalCheck) {
                $gst['trn_id']     = $tranId   = transaction_id('JNP');
                foreach ($journals as $journal) {
                    $gst['period_id']  = $periodId = $period->id;
                    $gst['chart_code'] = $chart_id = $journal->client_account_code->code;
                    $gst['net_amount'] = $journal->net_amount;

                    $type         = $journal->client_account_code->type;
                    $gst_code     = $journal->gst_code;
                    $gst_amount   = $journal->gst;
                    if ($type == 1) {
                        if (($gstEnabled == 0) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                            $gst['gross_amount'] = $journal->debit;
                        } elseif ($gstMethod == 2) {
                            $gst['gross_amount']       = $journal->debit;
                            $gst['gst_accrued_amount'] = $gst_amount;
                        } elseif ($gstMethod == 1) {
                            $gst['gross_amount']    = $journal->debit;
                            $gst['gst_cash_amount'] = $gst_amount;
                        }
                    } elseif ($type == 2) {
                        if (($gstEnabled == 0) && ($gst_code != 'GST' || $gst_code != 'CAP' || $gst_code != 'INP')) {
                            $gst['gross_amount'] = $journal->credit;
                        } elseif ($gstMethod == 1) {
                            $gst['gross_amount']    = $journal->credit;
                            $gst['gst_cash_amount'] = $gst_amount;
                        } elseif ($gstMethod == 2) {
                            $gst['gross_amount']       = $journal->credit;
                            $gst['gst_accrued_amount'] = $gst_amount;
                        }
                    }
                    Gsttbl::create($gst);

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
                    $ledger['debit']                  = $ledger['credit'] = 0;
                    $ledger['gst']                    = abs($gst_amount);

                    if ($type == 1) {
                        $ledger['debit']        = $gst['gross_amount'] > 0 ? abs($gst['gross_amount']) : 0;
                        $ledger['credit']       = $gst['gross_amount'] < 0 ? abs($gst['gross_amount']) : 0;
                        $ledger['balance_type'] = 1;
                    } elseif ($type == 2) {
                        $ledger['debit']        = $gst['gross_amount'] < 0 ? abs($gst['gross_amount']) : 0;
                        $ledger['credit']       = $gst['gross_amount'] > 0 ? abs($gst['gross_amount']) : 0;
                        $ledger['balance_type'] = 2;
                    }
                    // Leadger Data Calculation
                    GeneralLedger::create($ledger);

                    if ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP') {
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
                            $ledger['balance_type'] = 1;
                            $ledger['debit']        = abs($gst_amount);
                            if ($gst_amount < 0) {
                                $ledger['credit'] = abs($gst_amount);
                                $ledger['debit']  = 0;
                            }
                            $ledger['balance']                = $gst_amount;
                            $ledger['chart_id']               = $clearingAc->code;
                            $ledger['client_account_code_id'] = $clearingAc->id;
                            $ledger['narration']              = 'JNP_CLEARING';
                            $ledger['gst']                    = 0;
                        } elseif ($type == 2) {
                            $ledger['balance_type'] = 2;
                            $ledger['credit']       = abs($gst_amount);
                            if ($gst_amount < 0) {
                                $ledger['debit']  = abs($gst_amount);
                                $ledger['credit'] = 0;
                            }
                            $ledger['balance']                = $gst_amount;
                            $ledger['chart_id']               = $payableAc->code;
                            $ledger['client_account_code_id'] = $payableAc->id;
                            $ledger['narration']              = 'JNP_PAYABLE';
                            $ledger['gst']                    = 0;
                        }

                        GeneralLedger::create($ledger);
                    }
                }

                //RetailEarning Calculation
                RetainEarning::retain($clientId, $professionId, $date, $ledger, ['JNP', 'JNP']);

                // Retain Earning For each Transection
                RetainEarning::tranRetain($clientId, $professionId, $tranId, $ledger, ['JNP', 'JNP']);
                //RetailEarning Calculation End....

                try {
                    JournalEntry::where('is_posted', 0)->where('client_id', $clientId)
                        ->where('profession_id', $professionId)
                        ->update([
                            'is_posted'      => 1,
                            'journal_number' => $journal_number,
                            'date'           => $date,
                            'tran_id'        => $tranId
                        ]);
                    Alert::success('Journal Post Success');
                } catch (\Exception $e) {
                    return $e;
                    Alert::error('Journal Post Not Success');
                }
            } else {
                Alert::warning('Journal Data not found!');
            }
        } else {
            Alert::warning('Select a valid date which belongs to period date');
        }
        try {
            DB::commit();
            activity()
                ->performedOn(new GeneralLedger())
                ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Journal Posted'])
                ->log('Add/Edit Data > Journal Entry > ' . $client->fullname . ' > ' . $profession->name . ' Journal Input');
        } catch (\Exception $e) {
            //return $e->getMessage();
            DB::rollBack();
        }
        return back();
    }
}
