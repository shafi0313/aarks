<?php

namespace App\Http\Controllers\Reports;

use App\Models\Admin;
use App\Models\Client;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use App\Models\Frontend\CashBook;
use App\Models\BankStatementInput;
use \PDF;
use Illuminate\Support\Facades\DB;
use App\Models\BankStatementImport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\BankReconciliationAdmin;
use App\Exports\BankReconcilationExport;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Frontend\DedotrPaymentReceive;
use App\Models\Frontend\CreditorPaymentReceive;
use Illuminate\Support\Benchmark;

class BankReconcilationController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.bank_recon.index')) {
            return $error;
        }

        return view('admin.reports.bank_recon.index');
    }

    // public function getClient(Request $request)
    // {
    //     if ($error = $this->sendPermissionError('admin.bank_recon.index')) {
    //         return $error;
    //     }
    //     $clients = Client::where('first_name', 'like', '%' . $request->q . '%')->orWhere('company', 'like', '%' . $request->q . '%')
    //         ->select('id', 'first_name', 'last_name', 'company')
    //         ->get()
    //         ->map(function ($client) {
    //             return [
    //                 'id' => $client->id,
    //                 // 'id' => route('client-pro', $client->id),
    //                 "text" => $client->company . '-' . $client->full_name
    //             ];
    //         })->toArray();

    //     return $clients;
    // }
    public function getCodes(Request $request, Client $client)
    {
        if ($error = $this->sendPermissionError('admin.bank_recon.index')) {
            return $error;
        }
        $codes = ClientAccountCode::whereClientId($client->id)
            ->where('code', 'like', '551%')
            ->where(fn ($q) => $q->orWhere('name', 'like', '%' . $request->q . '%'))
            ->select('id', 'name', 'code')
            ->get();

        $get         = 'codes';
        $html = view('admin.reports.gst_recon.profession', compact('codes', 'client', 'get'))->render();
        return response()->json(['data'=>$html]);
    }
    public function report(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.bank_recon.index')) {
            return $error;
        }
        // BST, INP,PIN,PBP,JNP,CBE,
        $data = $request->validate([
            'client_id' => 'required',
            'code'      => 'required',
            'from_date' => 'required',
            'to_date'   => 'required',
        ]);
        $from_date = makeBackendCompatibleDate($data['from_date'])->format('Y-m-d');
        $to_date = makeBackendCompatibleDate($data['to_date'])->format('Y-m-d');
        $client    = Client::findOrFail($request->client_id);
        $transactions = GeneralLedger::where('client_id', $client->id)
            ->where('chart_id', $request->code)
            ->whereBetween('date', [$from_date, $to_date])
            ->orderBy('date')
            ->groupBy('transaction_id')
            ->pluck('transaction_id')->toArray();
        $code      = ClientAccountCode::where('client_id', $client->id)
            ->where('code', $data['code'])->first();

        $posted_recons = BankReconciliationAdmin::where('client_id', $client->id)
            ->whereIn('tran_id', $transactions)
            ->whereBetween('date', [$from_date, $to_date])
            ->where('is_check', 1)
            ->where('is_posted', 1)
            ->get(['tran_id', 'identifier']);
        $posted_trans = $posted_recons->pluck('tran_id')->toArray();
        $posted_identify = $posted_recons->pluck('identifier')->toArray();
        $bst = BankStatementImport::where('client_id', $client->id)
            ->where('account_code', '!=', $code->id)
            // ->whereNotIn('tran_id', $posted_trans)
            ->whereIn('tran_id', $transactions)
            ->where('is_posted', 1)
            ->whereBetween('date', [$from_date, $to_date])
            ->selectRaw('id,tran_id,narration,debit,credit,date')
            ->addSelect(DB::raw('(select name from client_account_codes where id = account_code) as name'))
            ->addSelect(DB::raw('(select code from client_account_codes where id = account_code) as code'))
            ->get()->toArray();

        $inp = BankStatementInput::where('client_id', $client->id)
            ->where('account_code', '!=', $code->id)
            // ->whereNotIn('tran_id', $posted_trans)
            ->whereIn('tran_id', $transactions)
            ->where('is_posted', 1)
            ->whereBetween('date', [$from_date, $to_date])
            ->selectRaw('id,tran_id,narration,debit,credit,date')
            ->addSelect(DB::raw('(select name from client_account_codes where id = account_code) as name'))
            ->addSelect(DB::raw('(select code from client_account_codes where id = account_code) as code'))
            ->get()->toArray();
        $pin = DedotrPaymentReceive::where('client_id', $client->id)
            ->where('chart_id', '!=', $code->code)
            // ->whereNotIn('tran_id', $posted_trans)
            ->whereIn('tran_id', $transactions)
            ->whereBetween('tran_date', [$from_date, $to_date])
            ->selectRaw('id,tran_id,tran_date as date,inv_no as narration,payment_amount as debit,deleted_at as credit')
            ->addSelect(DB::raw('(select name from client_account_codes where code = chart_id and client_id = client_id limit 1) as name'))
            ->addSelect(DB::raw('(select code from client_account_codes where code = chart_id and client_id = client_id limit 1) as code'))
            ->get()->toArray();
        $pbp = CreditorPaymentReceive::where('client_id', $client->id)
            ->where('chart_id', '!=', $code->code)
            // ->whereNotIn('tran_id', $posted_trans)
            ->whereIn('tran_id', $transactions)
            ->whereBetween('tran_date', [$from_date, $to_date])
            ->selectRaw('id,tran_id,tran_date as date,inv_no as narration,deleted_at as debit,payment_amount as credit')
            ->addSelect(DB::raw('(select name from client_account_codes where code = chart_id and client_id = client_id limit 1) as name'))
            ->addSelect(DB::raw('(select code from client_account_codes where code = chart_id and client_id = client_id limit 1) as code'))
            ->get()->toArray();
        $jnp = JournalEntry::where('client_id', $client->id)
            ->where('account_code', '!=', $code->id)
            // ->whereNotIn('tran_id', $posted_trans)
            ->whereIn('tran_id', $transactions)
            ->where('is_posted', 1)
            ->whereBetween('date', [$from_date, $to_date])
            ->selectRaw('id,tran_id,narration,debit,credit,date')
            ->addSelect(DB::raw('(select name from client_account_codes where id = account_code) as name'))
            ->addSelect(DB::raw('(select code from client_account_codes where id = account_code) as code'))
            ->get()->toArray();
        $cbe = CashBook::where('client_id', $client->id)
            ->where('chart_id', '!=', $code->code)
            // ->whereNotIn('tran_id', $posted_trans)
            ->whereIn('tran_id', $transactions)
            ->whereBetween('tran_date', [$from_date, $to_date])
            ->selectRaw('id,tran_id,narration,amount_debit as debit,amount_credit as credit, tran_date as date')
            ->addSelect(DB::raw('(select name from client_account_codes where code = chart_id and client_id = client_id limit 1) as name'))
            ->addSelect(DB::raw('(select code from client_account_codes where code = chart_id and client_id = client_id limit 1) as code'))
            ->get()->toArray();
        $trade = GeneralLedger::where('client_id', $client->id)
            ->whereIn('chart_id', [552100, 911999])
            ->whereIn('source', ['PIN', 'PBP'])
            // ->whereNotIn('transaction_id', $posted_trans)
            ->whereIn('transaction_id', $transactions)
            ->whereBetween('date', [$from_date, $to_date])
            ->selectRaw('id,transaction_id as tran_id,narration,debit,credit,date')
            ->addSelect(DB::raw('(select name from client_account_codes where code = chart_id and client_id = client_id limit 1) as name'))
            ->addSelect(DB::raw('(select code from client_account_codes where code = chart_id and client_id = client_id limit 1) as code'))
            ->get()->toArray();
        $recons = array_merge($bst, $inp, $pin, $pbp, $jnp, $cbe, $trade);
        array_multisort(array_column($recons, 'date'), SORT_ASC, $recons);
        // return $recons;
        $bank_recons = BankReconciliationAdmin::where('client_id', $client->id)
            ->whereIn('tran_id', $transactions)
            ->whereBetween('date', [$from_date, $to_date])
            ->where('is_check', 1)
            ->get();
        return view('admin.reports.bank_recon.report', compact('client', 'data', 'recons', 'bank_recons', 'posted_recons', 'posted_identify'));

        // $recons = GeneralLedger::where('client_id', $client->id)
        //     ->whereIn('transaction_id', $transactions)
        //     ->whereBetween('date', [$from_date, $to_date])
        //     ->orderBy('date')
        //     ->get();

        // return view('admin.reports.bank_recon.ledger-report', compact('client', 'data', 'recons'));
    }

    public function store(Request $request, Client $client)
    {
        if ($error = $this->sendPermissionError('admin.bank_recon.create')) {
            return $error;
        }
        // return
        $data = $request->validate([
            'date'       => 'required|array',
            'tran_id'    => 'required|array',
            'code_name'  => 'required|array',
            'chart_id'   => 'required|array',
            'identifier' => 'required|array',
            'narration'  => 'required|array',
            'debit'      => 'required|array',
            'credit'     => 'required|array',
            'diff'       => 'required|array',
            'diff_array' => 'required|array',
            // 'diff_array' => 'required|string',
            'type'       => 'required|string',
        ], [
            'diff.required' => 'Please check ammount',
            'diff_array.required' => 'Please check ammount',
        ]);
        $diff_array = $request->diff_array;
        // $diff_array = explode(',', $request->diff_array);
        if ($request->type == 'Print/PDF') {
            $reconcilations = BankReconciliationAdmin::whereClientId($client->id)
                    ->whereIn('tran_id', $request->tran_id)
                    // ->where('is_posted', 1)
                    // ->where('diff', '!=', 0)
                    ->get();
            // return view('admin.reports.bank_recon.print', compact(['client', 'request', 'reconcilations']));
            $pdf = PDF::loadView('admin.reports.bank_recon.print', compact(['client', 'request', 'reconcilations']));
            return $pdf->setWarnings(false)->stream();
        } elseif ($request->type == 'Export to excel') {
            // Export Report
            return Excel::download(new BankReconcilationExport($client, $data), 'bank_reconcilation.xlsx');
        } elseif ($request->is_posted == 1) {
            Alert::error('Error', 'This report has been posted already');
            return redirect()->back();
        }

        $is_posted = $request->type == 'Post'? 1 : 0;
        $tran_id = transaction_id('GSTR');
        DB::beginTransaction();
        foreach ($diff_array as $i => $value) {
            BankReconciliationAdmin::updateOrCreate([
                'client_id' => $client->id,
                'tran_id'   => $request->tran_id[$i],
                'identifier' => $request->identifier[$i],
            ], [
                'client_id'  => $client->id,
                'tran_id'    => $request->tran_id[$i],
                'date'       => $request->date[$i],
                'code_name'  => $request->code_name[$i],
                'chart_id'   => $request->chart_id[$i],
                'narration'  => $request->narration[$i],
                'debit'      => $request->debit[$i],
                'credit'     => $request->credit[$i],
                'identifier' => $request->identifier[$i],
                'diff'       => $value,
                'is_posted'  => $is_posted,
                'is_check'   => $value != 0 ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        try {
            DB::commit();
            toast('Bank Reconciliation Report has been saved successfully', 'success');
        } catch (\Exception $e) {
            DB::rollback();
            toast('Bank Reconciliation Report has not been saved successfully', 'error');
            return $e->getMessage();
        }
        return redirect()->back();
        // return redirect()->route('admin.reports.gst_recon.show', [$client, $profession, $period]);

        return $request->all();
    }
    public function permission(Request $request, Client $client)
    {
        if ($error = $this->sendPermissionError('admin.bank_recon.edit')) {
            return $error;
        }
        $request->validate([
            'password' => 'required|string|max:64',
        ]);
        $admin = Admin::findOrFail(admin()->id);
        if (Hash::check($request->password, $admin->password)) {
            $reconcilations = BankReconciliationAdmin::whereClientId($client->id)
                    ->where('is_posted', 1)
                    ->where('diff', '!=', 0)
                    ->update(['is_posted' => 0]);
            Alert::success('GST Reconciliation Report has been restore successfully');
            return redirect()->back();
        } else {
            Alert::error('Error', 'Password is incorrect');
            return redirect()->back();
        }
    }
}
