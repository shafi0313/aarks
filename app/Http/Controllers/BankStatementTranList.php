<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use App\Models\BankStatementInput;
use App\Models\BankStatementImport;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Actions\BankStatementActions\InputBS;
use App\Actions\BankStatementActions\ImportBS;

class BankStatementTranList extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.bs_tranlist.index')) {
            return $error;
        }
        $clients = getClientsWithPayment();
        return view('admin.imp_tran_list.index', compact('clients'));
    }

    public function profession(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.bs_tranlist.index')) {
            return $error;
        }
        return view('admin.imp_tran_list.select_profession', compact('client'));
    }

    public function getBalance(Request $request)
    {
        if ($request->ajax()) {
            $ledger_balances = GeneralLedger::where('client_id', $request->client)
                ->whereProfession_id($request->profession)
                ->whereChart_id($request->chart_id)
                ->where('source', '!=', 'OPN')
                ->get('balance');
            $inputs = BankStatementImport::where('client_id', $request->client)
                ->where('profession_id', $request->profession)
                ->whereIs_posted(0);
            $input_sum = $inputs->sum('credit') - $inputs->sum('debit');

            $get_balance = 0;
            foreach ($ledger_balances as $ledger_balance) {
                if ($ledger_balance->credit != 0 || $ledger_balance->debit != 0) {
                    $get_balance -= $ledger_balance->balance;
                } else {
                    $get_balance += $ledger_balance->balance;
                }
            }

            $balance = number_format($get_balance, 2);
            $current_balance = number_format($get_balance + $input_sum, 2);
            return response()->json(['balance' => $balance, 'current_balance' => $current_balance, 'status' => 200]);
        }
    }

    public function report(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.bs_tranlist.index')) {
            return $error;
        }
        $ledgers = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('balance', '!=', 0)
            ->where(function ($q) {
                $q->where('source', 'BST')
                    ->orWhere('source', 'INP');
            })
            ->where('chart_id', 'not like', '99999%')
            ->get(ledgerSetVisible());

        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Bank Statement Report'])
            ->log('Transaction List > Bank Statement List > ' . $client->fullname . ' > ' . $profession->name . ' > All List');

        return view('admin.imp_tran_list.report', compact('client', 'profession', 'ledgers'));
    }
    public function detailsReport(Client $client, Profession $profession, $tran_id, $src)
    {
        if ($error = $this->sendPermissionError('admin.bs_tranlist.index')) {
            return $error;
        }
        if ($src == 'BST') {
            return $this->import($client, $profession, $tran_id, $src);
        } else {
            return $this->input($client, $profession, $tran_id, $src);
        }
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Bank Statement Details Report'])
            ->log('Transaction List > Bank Statement Details report > ' . $client->fullname . ' > ' . $profession->name . ' > Details Report');
        return redirect()->route('index');
    }
    /*=============
    == Bank statement Import
    ==============*/
    protected function import($client, $profession, $tran_id, $src)
    {
        $bank = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('transaction_id', $tran_id)
            ->where('chart_id', 'like', '551%')
            ->where('source', 'BST')
            ->where('narration', 'BST_BANK')
            ->first(ledgerSetVisible());
            
        $codes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            // ->where('code', '!=', $bank->chart_id)
            ->orderBy('code')
            ->get(clientAccountCodeSetVisible());

        $imports = BankStatementImport::with('client_account_code')
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('tran_id', $tran_id)
            ->where('is_posted', 1)
            ->where('account_code', '!=', null)
            ->get();

        return view('admin.imp_tran_list.import', compact('codes', 'client', 'profession', 'imports', 'bank', 'tran_id'));
    }
    public function importUpdate(Request $request, Client $client, Profession $profession, $tran_id, ImportBS $importBS)
    {
        if ($error = $this->sendPermissionError('admin.bs_tranlist.edit')) {
            return $error;
        }
        // return $request;
        $this->validate($request, [
            'bank_account'  => 'required',
            'client_id'     => 'required',
            'profession_id' => 'required'
        ]);
        foreach ($request->inp as $i => $id) {
            $bank = BankStatementImport::find($id);
            $data = [
                "debit"     => $request->debit[$i],
                "credit"    => $request->credit[$i],
                "narration" => $request->narration[$i],
                "date"      => makeBackendCompatibleDate($request->date[$i]),
            ];
            $bank->update($data);
        }
        $importBS->update($request);
        return back();
    }
    public function importDestroy(Client $client, Profession $profession, BankStatementImport $bank_statement)
    {
        if ($error = $this->sendPermissionError('admin.bs_tranlist.delete')) {
            return $error;
        }

        if (periodLock($client->id, $bank_statement->date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }

        $inpCount = BankStatementImport::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('tran_id', $bank_statement->tran_id)
            ->where('is_posted', 1)
            ->count();
        if ($inpCount == 1) {
            Alert::error('You can not delete this record, because it is last record of this transaction', 'If you want to delete this transaction, please delete it from Bank Statement Transaction List');
            return back();
        }

        try {
            $bank_statement->forceDelete();
            Alert::success('Bank Statement Deleted', 'Now Please Click on Update');
        } catch (\Exception $e) {
            toast('Opps! server side problem.', 'error');
            // return $e->getMessage();
        }
        return back();
    }
    public function importDeleteAll(Client $client, Profession $profession, $tran_id)
    {
        if ($error = $this->sendPermissionError('admin.bs_tranlist.delete')) {
            return $error;
        }
        try {
            BankStatementImport::whereClientId($client->id)
                ->whereProfessionId($profession->id)
                ->where('tran_id', $tran_id)
                ->whereIsPosted(1)
                ->delete();

            Gsttbl::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('trn_id', $tran_id)
                ->where('source', 'BST')
                ->delete();

            $ledger = GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('transaction_id', $tran_id)
                ->where('source', 'BST')
                ->first();

            GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('transaction_id', $tran_id)
                ->where('source', 'BST')
                ->where('chart_id', '!=', 999999)
                ->delete();

            $this->retain($ledger, $client, $profession, 'BST');
            Alert::success('Bank Statement Deleted');
        } catch (\Exception $e) {
            toast('Opps! server side problem.', 'error');
            // return $e->getMessage();
        }
        return back();
    }

    /*=================
    == Bank statement Input
    ==================*/

    public function inpUpdateAccountCode(Request $request, $id)
    {
        try {
            $input = BankStatementInput::find($id);
            if (periodLock($input->client_id, ($input->date))) {
                return response()->json(['status'=>500,'message'=> 'Your enter data period is locked, check administration']);
            }
            $input->update(['account_code' => $request->accountCode]);
            return response()->json(['status'=>200,'message'=> 'Account code successfully updated.']);
        } catch (\Exception $e) {
            return response()->json(['status'=>500,'message'=> $e->getMessage()]);
        }
    }

    protected function input($client, $profession, $tran_id, $src)
    {
        $bank = GeneralLedger::where('transaction_id', $tran_id)
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('chart_id', 'like', '551%')
            ->where('narration', 'INP_BANK')->first();
        $codes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', '!=', $bank->chart_id)
            ->orderBy('code')
            ->get();
        $inputs = BankStatementInput::with('client_account_code')
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('tran_id', $tran_id)
            ->where('is_posted', 1)
            ->where('account_code', '!=', null)
            ->get();

        return view('admin.imp_tran_list.input', compact('codes', 'client', 'profession', 'bank', 'tran_id', 'inputs'));
    }
    public function inputUpdate(Request $request, Client $client, Profession $profession, $tran_id, InputBS $inputBS)
    {
        if ($error = $this->sendPermissionError('admin.bs_tranlist.edit')) {
            return $error;
        }
        $this->validate($request, [
            'bank_account'  => 'required',
            'client_id'     => 'required',
            'profession_id' => 'required'
        ]);
        foreach ($request->inp as $i => $inp) {
            $bank = BankStatementInput::find($inp);
            $data = [
                "debit"     => $request->debit[$i],
                "credit"    => $request->credit[$i],
                "narration" => $request->narration[$i],
                "date"      => makeBackendCompatibleDate($request->date[$i]),
            ];
            $bank->update($data);
        }
        $inputBS->update($request);
        return back();
    }
    
    public function inputDestroy(Client $client, Profession $profession, BankStatementInput $bank_statement)
    {
        if ($error = $this->sendPermissionError('admin.bs_tranlist.delete')) {
            return $error;
        }
        if (periodLock($client->id, $bank_statement->date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }

        $inpCount = BankStatementInput::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('tran_id', $bank_statement->tran_id)
            ->where('is_posted', 1)
            ->count();
        if ($inpCount == 1) {
            Alert::error('You can not delete this record, because it is last record of this transaction', 'If you want to delete this transaction, please delete it from Bank Statement Transaction List');
            return back();
        }

        try {
            $bank_statement->forceDelete();
            Alert::success('Bank Statement Deleted', 'Now Please Click on UPDATE');
        } catch (\Exception $e) {
            toast('Opps! server side problem.', 'error');
            // return $e->getMessage();
        }
        return back();
    }
    public function inputDeleteAll(Client $client, Profession $profession, $tran_id)
    {
        if ($error = $this->sendPermissionError('admin.bs_tranlist.delete')) {
            return $error;
        }
        try {
            BankStatementInput::whereClientId($client->id)
                ->whereProfessionId($profession->id)
                ->where('tran_id', $tran_id)
                ->whereIsPosted(1)->delete();
            Gsttbl::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('trn_id', $tran_id)
                ->where('source', 'INP')->delete();

            $ledger = GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('transaction_id', $tran_id)
                ->where('source', 'INP')->first();

            GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('transaction_id', $tran_id)
                ->where('source', 'INP')
                ->where('chart_id', '!=', 999999)
                ->delete();
            $this->retain($ledger, $client, $profession, 'INP');
            Alert::success('Bank Statement Deleted');
        } catch (\Exception $e) {
            toast('Opps! server side problem.', 'error');
            // return $e->getMessage();
        }
        return back();
    }
    protected function retain($gen_led, $client, $profession, $source)
    {
        //RetailEarning Calculation
        $tran_date = $gen_led->date;
        if (in_array($tran_date->format('m'), range(1, 6))) {
            $start_year = $tran_date->format('Y') - 1 . '-07-01';
            $end_year   = $tran_date->format('Y') . '-06-30';
        } else {
            $start_year = $tran_date->format('Y') . '-07-01';
            $end_year   = $tran_date->format('Y') + 1 . '-06-30';
        }

        $inRetain   = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '>=', $start_year)
            ->where('date', '<=', $end_year)
            ->where('chart_id', 'LIKE', '1%')
            ->where('source', $source)
            ->get();
        $inRetainData = 0;
        foreach ($inRetain as $intr) {
            if ($intr->balance_type == 2 && $intr->balance > 0) {
                $inRetainData += abs($intr->balance);
            } else {
                $inRetainData -= abs($intr->balance);
            }
        }
        $exRetain = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '>=', $start_year)
            ->where('date', '<=', $end_year)
            ->where('chart_id', 'LIKE', '2%')
            ->where('source', $source)
            ->get();
        $exRetainData = 0;
        foreach ($exRetain as $intr) {
            if ($intr->balance_type == 1 && $intr->balance > 0) {
                $exRetainData += abs($intr->balance);
            } else {
                $exRetainData -= abs($intr->balance);
            }
        }

        $retainData = $inRetainData - $exRetainData;

        $ledger['source']                 = $source;
        $ledger['narration']              = $source . ' Retain Earning';
        $ledger['chart_id']               = 999999;
        $ledger['client_account_code_id'] = $ledger['gst'] = 0;
        $ledger['balance']                = $retainData;

        if ($inRetainData > $exRetainData) {
            $ledger['credit'] = abs($retainData);
            $ledger['debit']  = 0;
        } else {
            $ledger['debit']  = abs($retainData);
            $ledger['credit'] = 0;
        }

        $retain = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '>=', $start_year)
            ->where('date', '<=', $end_year)
            ->where('chart_id', 999999)
            ->where('source', $source)
            ->first();
        if ($retain) {
            $retain->update($ledger);
        }
    }
}
