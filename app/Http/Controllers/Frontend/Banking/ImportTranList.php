<?php

namespace App\Http\Controllers\Frontend\Banking;

use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use App\Models\BankStatementInput;
use Illuminate\Support\Facades\DB;
use App\Models\BankStatementImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\InputBSRequest;
use RealRashid\SweetAlert\Facades\Alert;
use App\Actions\BankStatementActions\InputBS;
use App\Actions\BankStatementActions\ImportBS;
use App\Actions\BankStatementActions\InputBankStatementPost;

class ImportTranList extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.banking.imp-tran.select_activity', compact('client'));
    }
    public function report(Client $client, Profession $profession)
    {
        $ledgers = GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where(function ($q) {
                    $q->where('source', 'BST')
                    ->orWhere('source', 'INP');
                })
                ->get();
        return view('frontend.banking.imp-tran.report', compact('client', 'profession', 'ledgers'));
    }

    public function detailsReport(Client $client, Profession $profession, $tran_id, $src)
    {
        if ($src == 'BST') {
            return $this->import($client, $profession, $tran_id, $src);
        } else {
            return $this->input($client, $profession, $tran_id, $src);
        }
        return $src;
    }
    /*=============
    == Bankstatement Import
    ==============*/
    protected function import($client, $profession, $tran_id, $src)
    {
        $bank    = GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('transaction_id', $tran_id)
                ->where('chart_id', 'like', '551%')
                ->where('source', 'BST')
                ->where('narration', 'BST_BANK')->first();
        $codes = ClientAccountCode::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('code', '!=', $bank->chart_id)
                ->orderBy('code')
                ->get();
        $imports = BankStatementImport::with('client_account_code')
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('tran_id', $tran_id)
                ->where('is_posted', 1)
                ->where('account_code', '!=', null)
                ->get();

        return view('frontend.banking.imp-tran.import', compact('codes', 'client', 'profession', 'imports', 'bank', 'tran_id'));
    }
    public function importUpdate(Request $request, Client $client, Profession $profession, $tran_id, ImportBS $importBS)
    {
        // return $request;

        $this->validate($request, [
            'bank_account'  => 'required',
            'client_id'     => 'required',
            'profession_id' => 'required'
        ]);
        foreach ($request->chart_id as $i => $chart_id) {
            $bank = BankStatementImport::whereClientId($client->id)
                    ->whereProfessionId($profession->id)
                    ->whereAccountCode($request->account_id[$i])
                    ->whereIsPosted(1)->first();
            $data = [
                "debit"     => $request->debit[$i],
                "credit"    => $request->credit[$i],
            ];
            $bank->update($data);
        }
        $importBS->update($request);
        return back();
    }
    public function importDestroy(Client $client, Profession $profession, BankStatementImport $bank_statement)
    {
        try {
            $bank_statement->delete();
            Alert::success('Bankstatement Deleted', 'Now Please Click on UPDATE');
        } catch (\Exception $e) {
            toast('Opps! server side problem.', 'error');
            // return $e->getMessage();
        }
        return back();
    }
    public function importDeleteAll(Client $client, Profession $profession, $tran_id)
    {
        try {
            BankStatementImport::whereClientId($client->id)
            ->whereProfessionId($profession->id)
            ->where('tran_id', $tran_id)
            ->whereIsPosted(1)->delete();
            Gsttbl::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('trn_id', $tran_id)
                ->where('source', 'BST')->delete();

            $ledger = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('transaction_id', $tran_id)
            ->where('source', 'BST')->first();

            GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('transaction_id', $tran_id)
            ->where('source', 'BST')
            ->where('chart_id', '!=', 999999)
            ->delete();
            $this->retain($ledger, $client, $profession);
            Alert::success('Bankstatement Deleted');
        } catch (\Exception $e) {
            toast('Opps! server side problem.', 'error');
            // return $e->getMessage();
        }
        return back();
    }

    /*=================
    == Bankstatement Input
    ==================*/

    protected function input($client, $profession, $tran_id, $src)
    {
        $bank    = GeneralLedger::where('transaction_id', $tran_id)
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

        return view('frontend.banking.imp-tran.input', compact('codes', 'client', 'profession', 'bank', 'tran_id', 'inputs'));
    }
    public function inputUpdate(Request $request, Client $client, Profession $profession, $tran_id, InputBS $inputBS)
    {
        $this->validate($request, [
            'bank_account'  => 'required',
            'client_id'     => 'required',
            'profession_id' => 'required'
        ]);
        foreach ($request->chart_id as $i => $chart_id) {
            $bank = BankStatementInput::whereClientId($client->id)
                    ->whereProfessionId($profession->id)
                    ->whereAccountCode($request->account_id[$i])
                    ->whereIsPosted(1)->first();
            $data = [
                "debit"     => $request->debit[$i],
                "credit"    => $request->credit[$i],
            ];
            $bank->update($data);
        }
        $inputBS->update($request);
        return back();
    }
    public function inputDestroy(Client $client, Profession $profession, BankStatementImport $bank_statement)
    {
        try {
            $bank_statement->delete();
            Alert::success('Bankstatement Deleted', 'Now Please Click on UPDATE');
        } catch (\Exception $e) {
            toast('Opps! server side problem.', 'error');
            // return $e->getMessage();
        }
        return back();
    }
    public function inputDeleteAll(Client $client, Profession $profession, $tran_id)
    {
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
            ->where('chart_id', '!=', 999999)->delete();
            $this->retain($ledger, $client, $profession);
            Alert::success('Bankstatement Deleted');
        } catch (\Exception $e) {
            toast('Opps! server side problem.', 'error');
            // return $e->getMessage();
        }
        return back();
    }

    protected function retain($ledger, $client, $profession)
    {
        //RetailEarning Calculation
        $tran_date = $ledger->date;
        if (in_array($tran_date->format('m'), range(1, 6))) {
            $start_year = $tran_date->format('Y') - 1 . '-07-01';
            $end_year   = $tran_date->format('Y') . '-06-30';
        } else {
            $start_year = $tran_date->format('Y') . '-07-01';
            $end_year   = $tran_date->format('Y')+1 . '-06-30';
        }

        $inRetain   = GeneralLedger::where('client_id', $client->id)
                    ->where('profession_id', $profession->id)
                    ->where('date', '>=', $start_year)
                    ->where('date', '<=', $end_year)
                    ->where('chart_id', 'LIKE', '1%')
                    ->where('source', "INP")
                    ->get();
        $inRetainData = $inRetain->where('balance_type', 2)->sum('balance') - $inRetain->where('balance_type', 1)->sum('balance');

        $exRetain = GeneralLedger::where('client_id', $client->id)
                    ->where('profession_id', $profession->id)
                    ->where('date', '>=', $start_year)
                    ->where('date', '<=', $end_year)
                    ->where('chart_id', 'LIKE', '2%')
                    ->where('source', "INP")
                    ->get();
        $exRetainData = $exRetain->where('balance_type', 1)->sum('balance') - $exRetain->where('balance_type', 2)->sum('balance');

        $retainData = $inRetainData - $exRetainData;
        $data['balance'] = $retainData;

        if ($inRetainData > $exRetainData) {
            $data['credit'] = abs($retainData);
            $data['debit']  = 0;
        } else {
            $data['debit']  = abs($retainData);
            $data['credit'] = 0;
        }

        GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '>=', $start_year)
            ->where('date', '<=', $end_year)
            ->where('chart_id', 999999)
            ->where('source', 'INP')
            ->first()->update($data);
    }
}
