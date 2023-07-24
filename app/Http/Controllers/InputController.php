<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Actions\RetainEarning;
use App\Models\ClientAccountCode;
use App\Models\BankStatementInput;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\InputBSRequest;
use RealRashid\SweetAlert\Facades\Alert;
use App\Jobs\CalculateGeneralLedgerBalance;
use App\Actions\BankStatementActions\InputBS;
use App\Actions\BankStatementActions\InputBankStatementPost;

class InputController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.bs_input.index')) {
            return $error;
        }
        $clients = getClientsWithPayment();
        return view('admin.bs_input.index', compact('clients'));
    }

    public function showProfessions(Client $client)
    {
        //TO-DO
        //Permission Will be updated later
        if ($error = $this->sendPermissionError('admin.bs_input.index')) {
            return $error;
        }
        return view('admin.bs_input.select_profession', compact('client'));
    }

    public function getCodes(Request $request)
    {
        if ($request->ajax()) {
            $codes = ClientAccountCode::where('client_id', $request->client)
                ->where('profession_id', $request->profession)
                ->where('id', '!=', $request->id)
                ->where('code', 'not like', '99999%')
                ->orderBy('code')
                ->get();
            return response()->json(['codes' => $codes, 'status' => 200]);
        }
    }

    public function getBalance(Request $request)
    {
        if ($request->ajax()) {
            $ledger_balances = GeneralLedger::where('client_id', $request->client)
                ->whereProfession_id($request->profession)
                ->whereChart_id($request->chart_id)
                ->where('source', '!=', 'OPN')
                ->get('balance');
            $inputs = BankStatementInput::where('client_id', $request->client)
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
            $current_balance = number_format($get_balance + $input_sum,2);
            return response()->json(['balance' => $balance, 'current_balance' => $current_balance, 'status' => 200]);
        }
    }

    public function inputBS(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.bs_input.create')) {
            return $error;
        }
        // $client_account_codes = ClientAccountCode::where('client_id', $client->id)
        //                 ->where('profession_id', $profession->id)
        //                 ->orderBy('code')
        //                 ->get();
        $inputs = BankStatementInput::with('client_account_code')
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('is_posted', 0)
            ->get();
        $liquid_asset_account_codes = ClientAccountCode::where('additional_category_id', aarks('liquid_asset_id'))
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('code', 'asc')
            ->get();
        return view('admin.bs_input.input', compact('client', 'profession', 'inputs', 'liquid_asset_account_codes'));
    }
    public function bankStatementStore(InputBSRequest $request)
    {
        if ($error = $this->sendPermissionError('admin.bs_input.create')) {
            return $error;
        }
        $date = makeBackendCompatibleDate($request->date);

        if (periodLock($request->client_id, $date)) {
            return response()->json('Your enter data period is locked, check administration', 403);
        }

        $period = Period::where('client_id', $request->client_id)
            ->where('profession_id', $request->profession_id)
            // ->where('start_date', '<=', $date->format('Y-m-d'))
            ->where('end_date', '>=', $date->format('Y-m-d'))->first();
        if (!$period) {
            return response()->json('Period Not Found Please make sure you have period on this date', 403);
        }
        $bs_input = BankStatementInput::create([
            'account_code'  => $request->account_code,
            'date'          => $date,
            'debit'         => $request->debit ?: 0,
            'credit'        => $request->credit ?: 0,
            'client_id'     => $request->client_id,
            'profession_id' => $request->profession_id,
            'gst_code'      => $request->gst_code,
            'narration'     => $request->narration
        ]);
        $bs_input->load('client_account_code');
        return response()->json($bs_input, 200);
    }

    public function bankStatementDelete(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.bs_input.edit')) {
            return $error;
        }
        $bsi = BankStatementInput::where('id', $request->id)->first();
        if (periodLock($bsi->client_id, $bsi->date)) {
            return response()->json('Your enter data period is locked, check administration', 403);
        }
        $bsi->delete();
        return response()->json("Bank Statement Input Deleted", 200);
    }

    public function post(Request $request, InputBS $inputbs)
    {
        if ($error = $this->sendPermissionError('admin.bs_input.post')) {
            return $error;
        }
        $this->validate($request, [
            'bank_account'  => 'required',
            'client_id'     => 'required',
            'profession_id' => 'required'
        ]);
        // return
        $inputbs->post($request);
        return back();
    }



    // public function post(Request $request, InputBankStatementPost $inputBankStatementPost)
    // {
    //     $this->validate($request, [
    //         'bank_account' => 'required'
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $inputBankStatementPost->setClient(Client::find($request->client_id))
    //             ->setProfession(Profession::find($request->profession_id))
    //             ->setBankAccount(ClientAccountCode::find($request->bank_account))
    //             ->execute();

    //         Alert::success('Success', 'Action Successful');
    //         DB::commit();
    //     } catch (\Exception $exception) {
    //         DB::rollBack();
    //         Alert::error('Error', $exception->getMessage());
    //     }
    //     return back();
    // }

    public function deleteFromTransList(GeneralLedger $generalLedger)
    {
        if ($error = $this->sendPermissionError('admin.bs_input.edit')) {
            return $error;
        }

        $client     = Client::find($generalLedger->client_id);
        $profession = Profession::find($generalLedger->profession_id);
        GeneralLedger::where('transaction_id', $generalLedger->transaction_id)->delete();
        dispatch(new CalculateGeneralLedgerBalance($client, $profession));
        Alert::success('Success', 'Successfully Deleted');
        return back();
    }
}
