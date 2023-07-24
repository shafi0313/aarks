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

class JournalListController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.journal_list.index')) {
            return $error;
        }

        $clients = getClientsWithPayment();
        return view('admin.journal_entry.list.client', compact('clients'));
    }
    public function listProfession($id)
    {
        if ($error = $this->sendPermissionError('admin.journal_list.index')) {
            return $error;
        }
        $client = Client::with('professions')->findOrFail($id);
        return view('admin.journal_entry.list.profession', compact('client'));
    }
    public function trans(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.journal_list.index')) {
            return $error;
        }
        $journals = JournalEntry::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('is_posted', 1)
            ->orderBy('journal_number')
            ->get();
        if ($journals->count() <= 0) {
            Alert::error('No Data Found!');
            return back();
        }
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Journal List Show'])
            ->log('Add/Edit Data > Journal Entry > ' . $client->fullname . ' > ' . $profession->name . ' Journal Show');
        return view('admin.journal_entry.list.tran-list', compact('client', 'profession', 'journals'));
    }
    public function transShow(Client $client, Profession $profession, $tran)
    {
        if ($error = $this->sendPermissionError('admin.journal_list.index')) {
            return $error;
        }
        $journals = JournalEntry::with('client_account_code')
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('tran_id', $tran)
            ->get();
        if ($journals->count() <= 0) {
            Alert::error('No Data Found!');
            return back();
        }
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Journal List by transaction Show'])
            ->log('Add/Edit Data > Journal Entry > ' . $client->fullname . ' > ' . $profession->name . ' Journal list by transaction');
        return view('admin.journal_entry.list.tran-list', compact('client', 'profession', 'journals'));
        return view('admin.journal_entry.list.tran-show', compact('client', 'profession', 'journals'));
    }

    public function listEdit(JournalEntry $journal)
    {
        if ($error = $this->sendPermissionError('admin.journal_list.edit')) {
            return $error;
        }
        $journals = JournalEntry::with('client_account_code')->where('client_id', $journal->client_id)
            ->where('profession_id', $journal->profession_id)
            ->where('tran_id', $journal->tran_id)
            ->where('journal_number', $journal->journal_number)->get();
        $client = Client::findOrFail($journal->client_id);
        $profession = Profession::findOrFail($journal->profession_id);

        $client_account_codes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            // ->where('code', 'not like', '9%')
            ->orderBy('code')
            ->get();

        return view('admin.journal_entry.list.edit', compact('client', 'profession', 'journals', 'journal', 'client_account_codes'));
    }
    public function listDelete(JournalEntry $journal)
    {
        if ($error = $this->sendPermissionError('admin.journal_list.delete')) {
            return $error;
        }
        $client     = Client::findOrFail($journal->client_id);
        $profession = Profession::findOrFail($journal->profession_id);

        Gsttbl::where('client_id', $journal->client_id)
            ->where('source', 'JNP')
            ->where('trn_id', $journal->tran_id)->delete();

        // Retains Update start
        $inRetain   = GeneralLedger::where('client_id', $journal->client_id)
            ->where('profession_id', $journal->profession_id)
            ->where('source', 'JNP')
            ->where('chart_id', 'LIKE', '1%')
            ->where('transaction_id', $journal->tran_id)->get();

        $inRetainData = $exRetainData = 0;
        foreach ($inRetain as $intr) {
            if ($intr->balance_type == 2 && $intr->balance > 0) {
                $inRetainData += abs($intr->balance);
            } else {
                $inRetainData -= abs($intr->balance);
            }
        }

        $exRetain   = GeneralLedger::where('client_id', $journal->client_id)
            ->where('profession_id', $journal->profession_id)
            ->where('source', 'JNP')
            ->where('chart_id', 'LIKE', '2%')
            ->where('transaction_id', $journal->tran_id)->get();
        foreach ($exRetain as $intr) {
            if ($intr->balance_type == 1 && $intr->balance > 0) {
                $exRetainData += abs($intr->balance);
            } else {
                $exRetainData -= abs($intr->balance);
            }
        }

        if (in_array($journal->date->format('m'), range(1, 6))) {
            $start_year = $journal->date->format('Y') - 1 . '-07-01';
            $end_year   = $journal->date->format('Y') . '-06-30';
        } else {
            $start_year = $journal->date->format('Y') . '-07-01';
            $end_year   = $journal->date->format('Y') + 1 . '-06-30';
        }
        $retain = GeneralLedger::where('client_id', $journal->client_id)
            ->where('profession_id', $journal->profession_id)
            ->where('source', 'JNP')
            ->where('chart_id', 999999)
            ->where('date', '>=', $start_year)
            ->where('date', '<=', $end_year)
            ->first();
        $retainData = $retain->balance - ($inRetainData - $exRetainData);
        $data['balance']  = $retainData;

        if ($retainData > 0) {
            $data['credit'] = abs($retainData);
            $data['debit']  = 0;
        } else {
            $data['debit']  = abs($retainData);
            $data['credit'] = 0;
        }
        $retain->update($data);
        // Retain Update end


        GeneralLedger::where('client_id', $journal->client_id)
            ->where('profession_id', $journal->profession_id)
            ->where('source', 'JNP')
            ->where('chart_id', '!=', 999999)
            ->where('transaction_id', $journal->tran_id)->delete();


        $journals = JournalEntry::where('client_id', $journal->client_id)
            ->where('profession_id', $journal->profession_id)
            ->where('is_posted', 1)->count();
        if ($journals <= 0) {
            Alert::error('No Data Found!');
            return redirect()->route('journal_list_profession', $journal->client_id);
        }


        JournalEntry::where('client_id', $journal->client_id)
            ->where('profession_id', $journal->profession_id)
            ->where('tran_id', $journal->tran_id)
            ->where('is_posted', 1)->delete();
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Journal List Deleted'])
            ->log('Add/Edit Data > Journal Entry > ' . $client->fullname . ' > ' . $profession->name . ' Journal list Deleted');
        toast('Delete Successful!', 'success');
        return redirect()->route('journal_list_profession', $client->id);
    }

    public function listUpdate(Request $request, JournalEntry $journal)
    {
        // return $journal;
        if ($error = $this->sendPermissionError('admin.journal_list.edit')) {
            return $error;
        }

        $this->validate($request, [
            'date'           => 'required',
            'client_id'      => 'required',
            'profession_id'  => 'required',
            'journal_number' => 'required',
            'account_code'   => 'required|array',
            'code_id'        => 'required|array',
            'id'             => 'required|array',
            'narration'      => 'required|array',
            'type'           => 'required|array',
            'gst_code'       => 'required|array',
            'debit'          => 'required|array',
            'credit'         => 'required|array',
            'gst'            => 'required|array',
            'net_amount'     => 'required|array',
        ]);
        $client       = Client::findOrFail($journal->client_id);
        $profession   = Profession::findOrFail($journal->profession_id);
        $clientId     = $client->id;
        $professionId = $profession->id;
        $periodId     = 0;
        $gstMethod    = $client->gst_method;                              // 0=None,2=Accrued,1=Cash
        $gstEnabled   = $client->is_gst_enabled;                          // 1=YES , 0=NO

        $ledger['batch']           = $journal->journal_number;
        $gst['client_id']          = $clientId;
        $gst['profession_id']      = $professionId;
        $gst['period_id']          = $periodId;
        $gst['trn_id']             = $periodId;
        $gst['trn_date']           = $bsd = $journal->date;
        $gst['chart_code']         = 0;
        $gst['source']             = "JNP";
        $gst['gross_amount']       = 0;
        $gst['gst_accrued_amount'] = 0;
        $gst['gst_cash_amount']    = 0;
        $gst['net_amount']         = 0;

        $period = Period::where('client_id', $clientId)
            ->where('profession_id', $professionId)
            // ->where('start_date', '<=', $bsd)
            ->where('end_date', '>=', $bsd)->first();

        if (periodLock($clientId, $bsd)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        DB::beginTransaction();

        GeneralLedger::where('transaction_id', $journal->tran_id)->where('client_id', $clientId)->where('profession_id', $professionId)->where('source', 'JNP')->delete();

        Gsttbl::where('client_id', $clientId)->where('profession_id', $professionId)->where('trn_id', $journal->tran_id)->where('source', 'JNP')->delete();

        foreach ($request->id as $i => $id) {
            $gst['period_id']  = $periodId = $period->id;
            $gst['trn_id']     = $tranId   = $journal->tran_id;
            $gst['chart_code'] = $chart_id = $request->account_code[$i];

            $type         = $request->type[$i];
            $gst_code     = $request->gst_code[$i];
            $data = [
                "account_code"   => $request->code_id[$i],
                "journal_number" => $request->journal_number,
                "tran_id"        => $tranId,
                "date"           => $bsd,
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
                    $data['debit']      = -$request->credit[$i];
                    $data['gst']        = -$request->gst[$i];
                    $data['net_amount'] = -$request->net_amount[$i];
                    $data['credit']     = null;
                }
            }
            if ($type == 2) {
                if ($request->credit[$i] == 0) {
                    $data['credit']     = -$request->debit[$i];
                    $data['gst']        = -$request->gst[$i];
                    $data['net_amount'] = -$request->net_amount[$i];
                    $data['debit']      = null;
                }
            }

            $journal = JournalEntry::updateOrCreate([
                'id' => $id
            ], $data);



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
            Gsttbl::create($gst);
            // Ledger Calculations

            $ledger['chart_id']               = $chart_id;
            $ledger['date']                   = $bsd;
            $ledger['narration']              = $journal->narration;
            $ledger['source']                 = 'JNP';
            $ledger['client_id']              = $clientId;
            $ledger['profession_id']          = $professionId;
            $ledger['transaction_id']         = $tranId;
            $ledger['client_account_code_id'] = $request->code_id[$i];
            $ledger['balance']                = $gst['net_amount'];
            $ledger['debit']                  = $ledger['credit'] = 0;

            $ledger['gst'] = $gst['gst_accrued_amount'] == '' ? $gst['gst_cash_amount'] : $gst['gst_accrued_amount'];

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
            GeneralLedger::create($ledger);


            // Payable Clearing calculation
            if ($gst_code == 'GST' || $gst_code == 'CAP' || $gst_code == 'INP') {
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
                    $ledger['narration']              = 'JNP_CLEARING';
                    $ledger['gst']                    = 0;
                } elseif ($type == 2) {
                    $ledger['balance_type']           = 2;
                    $ledger['credit']                 = $ledger['balance']      = $ledger['gst'];
                    $ledger['debit']                  = 0;
                    $ledger['chart_id']               = $payableAc->code;
                    $ledger['client_account_code_id'] = $payableAc->id;
                    $ledger['narration']              = 'JNP_PAYABLE';
                    $ledger['gst']                    = 0;
                }
                GeneralLedger::create($ledger);
            }
        }


        //RetailEarning Calculation
        RetainEarning::retain($clientId, $professionId, $bsd, $ledger, ['JNP', 'JNP']);

        // Retain Earning For each Transection
        RetainEarning::tranRetain($clientId, $professionId, $tranId, $ledger, ['JNP', 'JNP']);
        //RetailEarning Calculation End....
        try {
            DB::commit();
            activity()
                ->performedOn(new GeneralLedger())
                ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Journal List Updated'])
                ->log('Add/Edit Data > Journal Entry > ' . $client->fullname . ' > ' . $profession->name . ' Journal list Updated');
            Alert::success('Journal Update Success');
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
            Alert::error('Journal Update Not Success');
        }
        return back();
    }
}
