<?php

namespace App\Http\Controllers\Frontend\Banking;

use App\Models\Client;
use App\Models\Profession;
use App\Models\Data_storage;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use App\Models\BankReconciliation;
use \PDF;
use App\Actions\GeneralLedgerAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BankReconcilationExport;
use App\Models\BankReconciliationLedger;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\ShowGeneralLedgerRequest;

class BankReconciliationController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->where('id', client()->id)->first();
        return view('frontend.banking.reconciliation.index', compact('client'));
    }
    public function date(Client $client, Profession $profession)
    {
        $codes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', 'like', '55%')
            ->orderBy('code')->get();
        return view('frontend.banking.reconciliation.date', compact('client', 'profession', 'codes'));
    }
    public function show(Request $request, GeneralLedgerAction $action)
    {
        // return $request;
        $end_date     = makeBackendCompatibleDate($request->end_date);
        $client       = Client::find($request->client_id);
        $profession   = Profession::find($request->profession_id);
        $account_code = ClientAccountCode::whereClientId($client->id)
            ->whereCode($request->account)->first();
        $recons = BankReconciliationLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '<=', $end_date->format('Y-m-d'))->get();
        $recons_posted = $recons->where('is_posted', 1)
            ->pluck('general_ledger_id')->toArray();
        $recons_saved = $recons->where('is_posted', 0)
            ->pluck('general_ledger_id')->toArray();
        $ledgers = GeneralLedger::whereNotIn('id', $recons_posted)
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->Where('chart_id', $account_code->code)
            ->where('date', '<=', $end_date)
            ->get();
        if ($request->balance_type == 1) {
            $physical = $request->balance . ' Dr';
            $physical_balance = $request->balance;
        } else {
            $physical = $request->balance . ' Cr';
            $physical_balance = -$request->balance;
        }
        $data = compact('account_code', 'ledgers', 'end_date', 'client', 'profession', 'physical_balance', 'physical', 'recons_saved');
        return view('frontend.banking.reconciliation.report', $data);
    }
    public function post(Request $request)
    {
        $data = $request->validate([
            "client_id"        => 'required|string',
            "profession_id"    => 'required|string',
            "physical_balance" => 'required|string',
            "date"             => 'required|string',
            "chart_id"         => 'required|string',
            "check"            => 'required|array',
            "balance"          => 'required|array',
            "ledger_id"        => 'required|array',
            "reconciled_dr"    => 'required|string',
            "reconciled_cr"    => 'required|string',
            "unreconciled_dr"  => 'required|string',
            "unreconciled_cr"  => 'required|string',
            "balance_diff"     => 'required|string',
            "type"             => 'required|string',
        ]);
        $data['date']    = $date    = makeBackendCompatibleDate($request->date);
        $data['tran_id'] = $tran_id = transaction_id();
        $data['is_bank'] = 1;

        $client     = Client::find($request->client_id);
        $profession = Profession::find($request->profession_id);

        if ($request->type == 'Print/PDF') {
            $reconcilations = BankReconciliationLedger::with('generalLedger')->whereClientId($request->client_id)
                    ->where('profession_id', $request->profession_id)
                    ->whereIn('general_ledger_id', $request->ledger_id)
                    ->where('is_posted', 0)
                    ->where('date', '<=', $date->format('Y-m-d'))
                    ->get();
            $bank_recon = BankReconciliation::whereClientId($request->client_id)
                    ->where('profession_id', $request->profession_id)
                    ->where('is_posted', 0)
                    ->where('date', '<=', $date->format('Y-m-d'))
                    ->first();
            // return view('frontend.banking.reconciliation.print', compact(['client', 'request', 'reconcilations','bank_recon]));
            $pdf = PDF::loadView('frontend.banking.reconciliation.print', compact(['client', 'request', 'reconcilations','bank_recon']));
            return $pdf->setWarnings(false)->stream();
        } elseif ($request->type == 'Export to excel') {
            // Export Report
            return Excel::download(new BankReconcilationExport($client, $data, 'front'), 'bank_reconcilation.xlsx');
        } elseif ($request->is_posted == 1) {
            Alert::error('Error', 'This report has been posted already');
            return redirect()->back();
        }
        // $chk = BankReconciliationLedger::where('client_id', $request->client_id)
        //     ->where('profession_id', $request->profession_id)
        //     ->where('date', '<=', $date->format('Y-m-d'))->count();
        $data['is_posted'] = $is_posted = $request->type == 'Post' ? 1 : 0;
        try {
            // if (!$chk) {
            foreach ($request->ledger_id as $i => $id) {
                BankReconciliationLedger::updateOrCreate([
                    "client_id"     => $request->client_id,
                    "profession_id" => $request->profession_id,
                    "chart_id"      => $request->chart_id,
                    'date'          => $date,
                ], [
                    "client_id"         => $request->client_id,
                    "profession_id"     => $request->profession_id,
                    "chart_id"          => $request->chart_id,
                    'date'              => $date,
                    'general_ledger_id' => $id,
                    'tran_id'           => $tran_id,
                    'is_posted'         => $is_posted,
                    'balance'           => $request->balance[$i],
                    'gl_balance'        => $request->check[$i],
                ]);
            }
            BankReconciliation::updateOrCreate([
                "client_id"     => $request->client_id,
                "profession_id" => $request->profession_id,
                "chart_id"      => $request->chart_id,
                'date'          => $date,
            ], $data);
            if ($is_posted) {
                Alert::success('Reconciliation Posted.');
            } else {
                Alert::success('Reconciliation Saved.');
            }
            // } else {
            //     Alert::error('You can not post this transaction at same date.');
            // }
        } catch (\Exception $e) {
            Alert::error('Server Side Error');
            // return $e->getMessage();
        }
        return redirect()->back();
    }
    public function transaction($transaction_id, $source, GeneralLedgerAction $action)
    {
        $data = $action->showTran($transaction_id, $source);
        return view('frontend.banking.reconciliation.show_transaction', $data);
    }
    public function transactionByDS($trn_id, $code)
    {
        $datas = Data_storage::where('chart_id', $code)->where('trn_id', $trn_id)->get();
        if ($datas->count()) {
            $codeName = ClientAccountCode::where('code', $code)->first();
            return view('frontend.banking.reconciliation.show_data-store_transaction', compact(['datas', 'codeName']));
        } else {
            toast('For details find the entry source report, such as  cash book report, journal list,bank transaction list', 'error');
            return back();
        }
    }
}
