<?php

namespace App\Http\Controllers\Frontend\Banking;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use App\Http\Controllers\Controller;
use App\Models\BankReconciliationLedger;
use RealRashid\SweetAlert\Facades\Alert;

class BankReconciliationStatementController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->where('id', client()->id)->first();
        return view('frontend.banking.reconciliation.statement.index', compact('client'));
    }
    public function date(Client $client, Profession $profession)
    {
        $codes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', 'like', '55%')
            ->orderBy('code')->get();
        return view('frontend.banking.reconciliation.statement.date', compact('client', 'profession', 'codes'));
    }
    public function report(Request $request, Client $client, Profession $profession)
    {
        $end_date = makeBackendCompatibleDate($request->end_date);
        $chk_recon = BankReconciliationLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', $end_date->format('Y-m-d'))->count();
        if (!$chk_recon) {
            Alert::warning('Please Check Date!', 'You can not perform this action.');
            return redirect()->back()->withInput();
        }
        $recons_id = BankReconciliationLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '<=', $end_date->format('Y-m-d'))
            ->pluck('general_ledger_id')->toArray();
        $code = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', $request->account)->first();
        $ledgers = GeneralLedger::whereNotIn('id', $recons_id)
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->Where('chart_id', $request->account)
            ->where('date', '<=', $end_date)
            ->get();
        return view('frontend.banking.reconciliation.statement.report', compact('client', 'ledgers', 'profession', 'end_date', 'code'));
    }
}
