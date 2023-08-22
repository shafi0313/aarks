<?php

namespace App\Http\Controllers\Reports;

use App\Models\Note;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\Reconcilation;
use App\Models\ReconcilationTax;
use \PDF;
use Illuminate\Support\Facades\DB;
use App\Exports\ReconcilationExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class GstReconcilationController extends Controller
{
    // GST/BAS (Cash Basis)
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.gst-reconciliation-for-tr.index')) {
            return $error;
        }

        return view('admin.reports.gst_recon.index');
    }

    public function getClient(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.gst-reconciliation-for-tr.index')) {
            return $error;
        }
        $clients = Client::where('first_name', 'like', '%' . $request->q . '%')->orWhere('company', 'like', '%' . $request->q . '%')
            ->select('id', 'first_name', 'last_name', 'company')
            ->get()
            ->map(function ($client) {
                return [
                    'id' => $client->id,
                    // 'id' => route('client-pro', $client->id),
                    "text" => $client->company . '-' . $client->full_name
                ];
            })->toArray();

        return $clients;
    }
    public function profession(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.gst-reconciliation-for-tr.index')) {
            return $error;
        }
        $professions = $client->professions;
        $get         = 'profession';
        $html        = view('admin.reports.gst_recon.profession', compact('professions', 'client', 'get'))->render();
        return response()->json(['data' => $html]);
    }
    public function period(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.gst-reconciliation-for-tr.index')) {
            return $error;
        }
        $periods = Period::where('client_id', $client->id)->where('profession_id', $profession->id)->groupBy('year')->get(['id', 'year']);
        $get         = 'period';
        $html        = view('admin.reports.gst_recon.profession', compact('periods', 'profession', 'client', 'get'))->render();
        return response()->json(['data' => $html]);
    }
    public function report(Request $request, Client $client, Profession $profession, Period $period)
    {
        if ($error = $this->sendPermissionError('admin.gst-reconciliation-for-tr.index')) {
            return $error;
        }
        $client_id         = $client->id;
        $profession_id     = $profession->id;

        // return view('admin.reports.gst_recon.balance', compact('client', 'profession', 'period'));

        $dateFrom          = ($period->year - 1) . '-07-01';
        $dateTo            = ($period->year) . '-06-30';
        // $dateFrom          = makeBackendCompatibleDate('01/07/'.(int) $period->year-1)->format('Y-m-d');
        // $dateTo            = makeBackendCompatibleDate('30/06/'.(int) $period->year)->format('Y-m-d');
        $expense_code_from = '245000';
        $expense_code_to   = '245999';
        $w1_from           = '245100';
        $w1_to             = '245199';

        $periods = Period::whereClientId($client_id)->whereProfessionId($profession_id)->where('year', $period->year)->pluck('id')->toArray();


        // $income = Gsttbl::where('client_id', $client_id)
        //     ->where('profession_id', $profession_id)
        //     ->whereBetween('trn_date', [$dateFrom, $dateTo])
        //     // ->where('source', '!=', 'INV')
        //     ->where('chart_code', 'like', '1%')
        //     ->get(['gst_cash_amount', 'gross_amount', 'trn_date']);

        $income = Gsttbl::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('source', '!=', 'INV')
            ->where('chart_code', 'like', '1%')
            ->get(['gst_cash_amount', 'gross_amount', 'trn_date']); // _________G1 & A1_________

        $expense = Gsttbl::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('source', '!=', 'INV')
            ->where(function ($q) {
                $q->where('chart_code', 'like', '2%')
                    ->orWhere('chart_code', 'like', '5%');
            })->get(['gst_cash_amount', 'gross_amount', 'trn_date']); // _________1B_________

        $incomeNonGst = Gsttbl::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', 'like', '1%')
            ->where('gst_accrued_amount', '<', 0)
            ->where('gst_cash_amount', '<', 0)
            ->get(['gst_cash_amount', 'gross_amount', 'net_amount', 'trn_date']); // _________G3_________

        $asset = Gsttbl::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', 'like', '56%')
            ->get(['gst_cash_amount', 'gross_amount', 'trn_date']); // _________G10_________

        $expense_code = Gsttbl::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->whereNotBetween('chart_code', [$expense_code_from, $expense_code_to])
            ->where('chart_code', 'like', '2%')
            ->get(['gst_cash_amount', 'gross_amount', 'trn_date']); // _________G11_________



        $w1 = Gsttbl::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->whereBetween('chart_code', [$w1_from, $w1_to])
            ->get(['gst_cash_amount', 'gross_amount', 'trn_date']); // _________W1_________

        $w2 = Gsttbl::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', '245103')
            ->get(['gst_cash_amount', 'gross_amount', 'trn_date']); // _________w2_________

        // Income Tax
        $creditTax   = GeneralLedger::where('date', '>=', $dateFrom)
            ->where('date', '<=', $dateTo)
            ->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->where('chart_id', 'like', '1%')
            ->get();
        $totalCredit = $creditTax->where('balance_type', 2)->sum('credit') - $creditTax->where('balance_type', 1)->sum('debit');

        $debitTax  = GeneralLedger::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->where('date', '>=', $dateFrom)
            ->where('date', '<=', $dateTo)
            ->where('chart_id', 'like', '2%')->get();
        $totalDebit = $debitTax->where('balance_type', 2)->sum('debit') - $debitTax->where('balance_type', 1)->sum('credit');
        // ================
        $incomeTax   = GeneralLedger::where('date', '>=', $dateFrom)
            ->where('date', '<=', $dateTo)
            ->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->where('chart_id', 'like', '1%')
            ->get();
        $totalIncome = $incomeTax->where('balance_type', 1)->sum('balance') - $incomeTax->where('balance_type', 2)->sum('balance');

        $expTax  = GeneralLedger::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->where('date', '>=', $dateFrom)
            ->where('date', '<=', $dateTo)
            ->where('chart_id', 'like', '2%')->get();
        $totalExpense = $expTax->where('balance_type', 1)->sum('balance') - $expTax->where('balance_type', 2)->sum('balance');
        // Income Tax

        // return $income;

        $recons = Reconcilation::whereClientId($client->id)
            ->whereProfessionId($profession->id)
            ->wherePeriodId($period->id)
            ->where('year', $period->year)
            ->get();
        $note = Note::whereClientId($client->id)
            ->whereProfessionId($profession->id)
            ->where('year', $period->year)
            ->where('model', 'reconcilation')
            ->first();
        $recons_taxes = ReconcilationTax::whereClientId($client->id)
            ->whereProfessionId($profession->id)
            ->wherePeriodId($period->id)
            ->where('year', $period->year)
            ->get();
        // return $recons->where('item', 'g1')->first();

        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'report' => 'Gst Reconciliation Report'])
            ->log('Report > Gst Reconciliation Report > ' . $client->fullname);
        return view('admin.reports.gst_recon.balance', compact(['client', 'dateFrom', 'dateTo', 'income', 'asset', 'expense_code', 'expense', 'w1', 'w2', 'incomeNonGst', 'period', 'totalIncome', 'totalExpense', 'totalCredit', 'totalDebit', 'profession', 'recons', 'recons_taxes', 'note']));
    }

    public function store(Request $request, Client $client, Profession $profession, Period $period)
    {
        if ($error = $this->sendPermissionError('admin.gst-reconciliation-for-tr.save')) {
            return $error;
        }
        $request->validate([
            'jul_sep_gl'  => 'required|array',
            'jul_sep_ato' => 'required|array',

            'oct_dec_gl'  => 'required|array',
            'oct_dec_ato' => 'required|array',

            'jan_mar_gl'  => 'required|array',
            'jan_mar_ato' => 'required|array',

            'apr_jun_gl'  => 'required|array',
            'apr_jun_ato' => 'required|array',

            'bas'    => 'required|array',
            'report' => 'required|array',
            'ato'    => 'required|array',
        ]);

        if ($request->type == 'Print/PDF') {
            $reconcilations = Reconcilation::whereClientId($client->id)
                ->whereProfessionId($profession->id)
                ->wherePeriodId($period->id)
                ->where('year', $period->year)
                ->get();
            $taxes = ReconcilationTax::whereClientId($client->id)
                ->whereProfessionId($profession->id)
                ->wherePeriodId($period->id)
                ->where('year', $period->year)
                ->get();
            // return view('admin.reports.gst_recon.print', compact(['client', 'request', 'profession', 'period', 'reconcilations', 'taxes']));
            $pdf = PDF::loadView('admin.reports.gst_recon.print', compact(['client', 'request', 'profession', 'period', 'reconcilations', 'taxes']));
            return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->stream();
        } elseif ($request->type == 'Export to excel') {
            // Export Report
            return Excel::download(new ReconcilationExport($client, $profession, $period), 'gst_reconcilation.xlsx');
        } elseif ($request->is_posted == 1) {
            Alert::error('Error', 'This report has been posted already');
            return redirect()->back();
        }


        $item            = ['g1', 'g3', '1a', 'g11', '1b', 'w1', 'w2', 'g10', '9'];
        $particular      = [
            'Sales before GST',
            'GST amount in sales',
            'Sales After GST',
            'Expesnes before GST',
            'GST ON EXPESNSE',
            'Expense after GST',
            'Total wages',
            'PAYG'
        ];
        $is_posted = $request->type == 'Post' ? 1 : 0;
        $recons = $tax = [];
        $tran_id = transaction_id('GSTR');
        DB::beginTransaction();
        foreach ($request->jul_sep_gl as $key => $value) {
            Reconcilation::updateOrCreate([
                'client_id'     => $client->id,
                'profession_id' => $profession->id,
                'period_id'     => $period->id,
                'year'          => $period->year,
                'item'          => $item[$key],
            ], [
                'client_id'     => $client->id,
                'profession_id' => $profession->id,
                'period_id'     => $period->id,
                'tran_id'       => $tran_id,
                'year'          => $period->year,
                'item'          => $item[$key],
                'jul_sep_gl'    => $value,
                'is_posted'     => $is_posted,
                'jul_sep_ato'   => $request->jul_sep_ato[$key],
                'oct_dec_gl'    => $request->oct_dec_gl[$key],
                'oct_dec_ato'   => $request->oct_dec_ato[$key],
                'jan_mar_gl'    => $request->jan_mar_gl[$key],
                'jan_mar_ato'   => $request->jan_mar_ato[$key],
                'apr_jun_gl'    => $request->apr_jun_gl[$key],
                'apr_jun_ato'   => $request->apr_jun_ato[$key],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
        foreach ($particular as $key => $value) {
            ReconcilationTax::updateOrCreate([
                'client_id'     => $client->id,
                'profession_id' => $profession->id,
                'period_id'     => $period->id,
                'year'          => $period->year,
                'particular'    => $value,
            ], [
                'client_id'     => $client->id,
                'profession_id' => $profession->id,
                'period_id'     => $period->id,
                'tran_id'       => $tran_id,
                'year'          => $period->year,
                'particular'    => $value,
                'is_posted'     => $is_posted,
                'bas'           => $request->bas[$key],
                'report'        => $request->report[$key],
                'ato'           => $request->ato[$key],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
        try {
            DB::commit();
            toast('GST Reconciliation Report has been saved successfully', 'success');
        } catch (\Exception $e) {
            DB::rollback();
            toast('GST Reconciliation Report has not been saved successfully', 'error');
            #return $e->getMessage();
        }
        return redirect()->back();
        // return redirect()->route('admin.reports.gst_recon.show', [$client, $profession, $period]);

        return $request->all();
    }
    public function permission(Request $request, Client $client, Profession $profession, Period $period)
    {
        $request->validate([
            'password' => 'required|string|max:64',
        ]);
        $admin = Admin::findOrFail(admin()->id);
        if (Hash::check($request->password, $admin->password)) {
            Reconcilation::whereClientId($client->id)
                ->whereProfessionId($profession->id)
                ->wherePeriodId($period->id)
                ->where('year', $period->year)
                ->whereIsPosted(1)
                ->update(['is_posted' => 0]);
            ReconcilationTax::whereClientId($client->id)
                ->whereProfessionId($profession->id)
                ->wherePeriodId($period->id)
                ->where('year', $period->year)
                ->whereIsPosted(1)
                ->update(['is_posted' => 0]);
            Alert::success('GST Reconciliation Report has been restore successfully');
            return redirect()->back();
        } else {
            Alert::error('Error', 'Password is incorrect');
            return redirect()->back();
        }
    }

    public function saveNote(Request $request, Client $client, Profession $profession)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'model'   => 'required|string|max:255',
            'year'    => 'required|integer',
        ], [
            '*.required' => 'Note is required',
        ]);
        $note = Note::updateOrCreate([
            'client_id'     => $client->id,
            'profession_id' => $profession->id,
            'model'         => $request->model,
        ], [
            'client_id'     => $client->id,
            'profession_id' => $profession->id,
            'model'         => $request->model,
            'year'          => $request->year,
            'content'       => $request->content,
        ]);
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Note has been saved successfully', 'note' => $note]);
        } else {
            toast('Note has been saved successfully.', 'success');
            return redirect()->back();
        }
        return abort(404);
    }
}
