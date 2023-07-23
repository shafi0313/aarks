<?php

namespace App\Http\Controllers\Frontend\Banking;

use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use App\Models\BankStatementInput;
use App\Http\Controllers\Controller;
use App\Http\Requests\InputBSRequest;
use RealRashid\SweetAlert\Facades\Alert;
use App\Jobs\CalculateGeneralLedgerBalance;
use App\Actions\BankStatementActions\InputBS;

class ClientBsInputController extends Controller
{
    public function index()
    {
        $client = Client::findOrFail(client()->id);
        return view('frontend.banking.input.index', compact('client'));
    }
    public function inputBS(Client $client, Profession $profession)
    {
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
        return view('frontend.banking.input.input', compact('client', 'profession', 'inputs', 'liquid_asset_account_codes'));
    }
    public function getCodes(Request $request)
    {
        if ($request->ajax()) {
            $codes = ClientAccountCode::where('client_id', $request->client)
                        ->where('profession_id', $request->profession)
                        ->where('id', '!=', $request->id)
                        ->orderBy('code')
                        ->get();
            return response()->json(['codes'=>$codes,'status'=>200]);
        }
    }
    public function bankStatementStore(InputBSRequest $request)
    {
        $date = makeBackendCompatibleDate($request->date);

        if (periodLock($request->client_id, $date)) {
            return response()->json('Your enter data period is locked, check administration', 403);
        }

        $period     = Period::where('client_id', $request->client_id)
                    ->where('profession_id', $request->profession_id)
                    // ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)->first();
        if (!$period) {
            return response()->json('Period Not Found Please make sure you have period on this date', 403);
        }
        $bs_input = BankStatementInput::create([
            'account_code'  => $request->account_code,
            'date'          => $date,
            'debit'         => $request->debit ? : 0,
            'credit'        => $request->credit ? : 0,
            'client_id'     => $request->client_id,
            'profession_id' => $request->profession_id,
            'gst_code'      => $request->gst_code,
            'narration'     => $request->narration
        ]);
        $bs_input->load('client_account_code');
        return response()->json($bs_input, 200);
    }
    public function post(Request $request, InputBS $inputbs)
    {
        $this->validate($request, [
            'bank_account'  => 'required',
            'client_id'     => 'required',
            'profession_id' => 'required'
        ]);
        $inputbs->post($request);
        return back();
    }

    public function bankStatementDelete(Request $request)
    {
        $bsi = BankStatementInput::where('id', $request->id)->first();
        if (periodLock($bsi->client_id, $bsi->date)) {
            return response()->json('Your enter data period is locked, check administration', 403);
        }
        $bsi->delete();
        return response()->json("Bank Statement Input Deleted", 200);
    }

    public function deleteFromTransList(GeneralLedger $generalLedger)
    {
        $client     = Client::find($generalLedger->client_id);
        $profession = Profession::find($generalLedger->profession_id);
        GeneralLedger::where('transaction_id', $generalLedger->transaction_id)->delete();
        dispatch(new CalculateGeneralLedgerBalance($client, $profession));
        Alert::success('Success', 'Successfully Deleted');
        return back();
    }
}
