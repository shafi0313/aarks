<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Payable;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\IndustryCategory;
use App\Models\MasterAccountCode;
use \PDF;
use Illuminate\Support\Facades\DB;
use App\Models\AccountCodeCategory;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class ReportController extends Controller
{
    public function blank()
    {
        return view('admin.blank');
    }

    public function payable(Request $request)
    {
        try {
            $payable =  Payable::where('client_id', $request->client_id)->where('profession_id', $request->profession_id)->first();
            if ($payable != '') {
                $payable->update(['payable' => $request->payable, 'client_id' => $request->client_id, 'profession_id' => $request->profession_id]);
            } else {
                Payable::create(['payable' => $request->payable, 'client_id' => $request->client_id, 'profession_id' => $request->profession_id]);
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }



    // Accumulated P/L GST Exclusive
    public function accumExclindex()
    {
        if ($error = $this->sendPermissionError('admin.accum_excl.index')) {
            return $error;
        }
        $clients = Client::leftJoin('client_payment_lists', 'clients.id', '=', 'client_payment_lists.client_id')
            ->select('clients.id','clients.company', 'clients.first_name','clients.last_name','clients.email','clients.phone',
                    'client_payment_lists.status', 'client_payment_lists.is_expire', 'client_payment_lists.status')
            ->orderBy('client_payment_lists.status', 'desc')
            ->orderBy('client_payment_lists.is_expire', 'desc')
            ->get();
        return view('admin.accum.excl.index', compact('clients'));
    }
    public function accumExclPeriod(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.accum_excl.index')) {
            return $error;
        }
        return view('admin.accum.excl.report_period', compact(['client']));
    }
    public function accumExcleRport(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.accum_excl.index')) {
            return $error;
        }
        if ($request->form_date != '' and $request->to_date != '') {
            $form_date  = makeBackendCompatibleDate($request->form_date)->format('d/m/Y');
            $to_date    = makeBackendCompatibleDate($request->to_date)->format('d/m/Y');
            $start_date = makeBackendCompatibleDate($request->form_date)->format('Y-m-d');
            $end_date   = makeBackendCompatibleDate($request->to_date)->format('Y-m-d');

            $client_id     = $request->client_id;
            $client        = Client::find($client_id);
            $totalIncome   = GeneralLedger::select('*', DB::raw("(sum(credit)-SUM(gst)) as totalIncome"))
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->where('chart_id', 'like', '1%')
                ->first();
            $totalExpense  = GeneralLedger::select('*', DB::raw("(sum(debit)-SUM(gst)) as totalExpense"))
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->where('chart_id', 'like', '2%')
                ->first();

            $incomeCodes = GeneralLedger::with('client_account_code')
                ->select('*', DB::raw('sum(balance) as inBalance'))
                ->where('chart_id', 'like', '1%')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
            $expensCodes = GeneralLedger::with('client_account_code')
                ->select('*', DB::raw('sum(balance) as exBalance'))
                ->Where('chart_id', 'like', '2%')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
            // if($expensCodes){
            //     return $expensCodes;
            // }
            $checkLedger = GeneralLedger::where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client_id)
                ->get();
            if ($checkLedger->count()) {
                activity()
                    ->performedOn(new GeneralLedger())
                    ->withProperties(['client' => $client->fullname, 'report' => 'Profit Loss GST Accum Excl Report'])
                    ->log('Report > Profit Loss GST Accum Excl Report > '.$client->fullname);
                return view('admin.accum.excl.final_report', compact(['to_date', 'form_date', 'client', 'totalIncome', 'totalExpense', 'incomeCodes', 'expensCodes']));
            } else {
                Alert::warning('There was no records matching with input dates!');
                return back();
            }
        } else {
            Alert::warning('Please Check input Dates');
            return back();
        }
    }

    // Accumulated P/L GST Inclusive
    public function accumInclindex()
    {
        if ($error = $this->sendPermissionError('admin.accum_incl.index')) {
            return $error;
        }
        $clients = Client::leftJoin('client_payment_lists', 'clients.id', '=', 'client_payment_lists.client_id')
            ->select('clients.id','clients.company', 'clients.first_name','clients.last_name','clients.email','clients.phone',
                    'client_payment_lists.status', 'client_payment_lists.is_expire', 'client_payment_lists.status')
            ->orderBy('client_payment_lists.status', 'desc')
            ->orderBy('client_payment_lists.is_expire', 'desc')
            ->get();
        return view('admin.accum.incl.index', compact('clients'));
    }
    public function accumInclPeriod(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.accum_incl.index')) {
            return $error;
        }
        return view('admin.accum.incl.report_period', compact(['client']));
    }
    public function accumIncleRport(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.accum_incl.index')) {
            return $error;
        }
        if ($request->form_date != '' and $request->to_date != '') {
            $form_date = makeBackendCompatibleDate($request->form_date)->format('d/m/Y');
            $to_date   = makeBackendCompatibleDate($request->to_date)->format('d/m/Y');
            $start_date = makeBackendCompatibleDate($request->form_date)->format('Y-m-d');
            $end_date   = makeBackendCompatibleDate($request->to_date)->format('Y-m-d');

            $client_id = $request->client_id;
            $client = Client::find($client_id);
            $totalIncome  = GeneralLedger::select('*', DB::raw("sum(credit) as totalIncome"))
                ->where('client_id', $client_id)
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('chart_id', 'like', '1%')
                ->first();
            $totalExpense  = GeneralLedger::select('*', DB::raw("sum(debit) as totalExpense"))
                ->where('client_id', $client_id)
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('chart_id', 'like', '2%')
                ->first();

            $incomeCodes = GeneralLedger::with('client_account_code')
                ->select('*', DB::raw('sum(credit) as inBalance'))
                ->where('client_id', $client_id)
                ->where('chart_id', 'like', '1%')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
            $expensCodes = GeneralLedger::with('client_account_code')
                ->select('*', DB::raw('sum(debit) as exBalance'))
                ->where('client_id', $client_id)
                ->Where('chart_id', 'like', '2%')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
            $checkLedger = GeneralLedger::where('client_id', $client_id)
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->get();

            if ($checkLedger->count()) {
                activity()
                ->performedOn(new GeneralLedger())
                ->withProperties(['client' => $client->fullname, 'report' => 'Profit Loss GST Accum Incl Report'])
                ->log('Report > Profit Loss GST Accum Incl Report');
                return view('admin.accum.incl.final_report', compact(['to_date', 'form_date', 'client', 'totalIncome', 'totalExpense', 'incomeCodes', 'expensCodes']));
            } else {
                Alert::warning('There was no data matching with input date!');
                return back();
            }
        } else {
            Alert::warning('Please Check input Dates');
            return back();
        }
    }


    // balance_sheet
    public function balance_sheet_index()
    {
        if ($error = $this->sendPermissionError('admin.balance_sheet.index')) {
            return $error;
        }

        $clients = Client::leftJoin('client_payment_lists', 'clients.id', '=', 'client_payment_lists.client_id')
            ->select('clients.id','clients.company', 'clients.first_name','clients.last_name','clients.email','clients.phone',
                    'client_payment_lists.status', 'client_payment_lists.is_expire', 'client_payment_lists.status')
            ->orderBy('client_payment_lists.status', 'desc')
            ->orderBy('client_payment_lists.is_expire', 'desc')
            ->get();
        return view('admin.balance_sheet.index', compact('clients'));
    }
    public function balance_sheet_select_profession($id)
    {
        if ($error = $this->sendPermissionError('admin.balance_sheet.index')) {
            return $error;
        }
        $client = Client::with('professions')->findOrFail($id);
        return view('admin.balance_sheet.select_profession', compact('client'));
    }
    public function balance_sheet_select_date(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.balance_sheet.index')) {
            return $error;
        }
        return view('admin.balance_sheet.select_date', compact(['client', 'profession']));
    }
    public function balance_sheet_final_report(Request $request, Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.balance_sheet.index')) {
            return $error;
        }
        $date = makeBackendCompatibleDate($request->date);
        $end_date = $date->format('Y-m-d');
        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $start_date = $date->format('Y') . '-07-01';
        } else {
            $start_date = $date->format('Y') - 1 . '-07-01';
        }
        $industryCategories = IndustryCategory::pluck('name', 'id');
        $accountCodeCategories = AccountCodeCategory::with('subCategory', 'industryCategories')
            ->where('type', 1)->where(function ($q) {
                $q->where('code', 'like', '5%')
                    ->orWhere('code', 'like', '9%');
            })
            ->get();
        $masterAccountCodes = MasterAccountCode::where(function ($q) {
            $q->where('code', 'like', '5%')
                ->orWhere('code', 'like', '9%');
        })
            ->orderBy('code')
            ->get();
        $ledgers = GeneralLedger::select('*', DB::raw("sum(balance) as sheet_balance"))
            ->where('date', '<=', $end_date)
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            // ->where('chart_id', '!=', 999999)
            // ->where('chart_id', '!=', 999998)
            ->groupBy('chart_id')
            ->orderBy('chart_id')
            ->get();
        $retain = GeneralLedger::select('debit', 'credit', 'balance_type', DB::raw("sum(balance) as totalRetain"))
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '<', $start_date)
            ->where('chart_id', 999999)
            ->groupBy('chart_id')
            ->first();
        $CRetain = GeneralLedger::select('debit', 'credit', 'balance_type', DB::raw("sum(balance) as CRetain"))
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '>=', $start_date)
            ->where('date', '<=', $end_date)
            ->where('chart_id', 999998)
            ->first();
        $totalAsset =  GeneralLedger::select(DB::raw("sum(balance) as asset_balance"))->where('chart_id', 'like', '5%')->where('date', '<=', $end_date)->where('profession_id', $profession->id)->where('client_id', $client->id)->first();
        $totalLaila =  GeneralLedger::select(DB::raw("sum(balance) as laila_balance"))->where('chart_id', 'like', '5%')->where('date', '<=', $end_date)->where('profession_id', $profession->id)->where('client_id', $client->id)->first();
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Balance Sheet Report'])
            ->log('Report > Balance Sheet Report > '.$client->fullname .' > '. $profession->name);
        return view('admin.balance_sheet.final_report', compact('totalAsset', 'totalLaila', 'accountCodeCategories', 'masterAccountCodes', 'industryCategories', 'ledgers', 'date', 'client', 'retain', 'CRetain', 'profession'));
    }


    // ratio_analysis
    public function ratio_analysis_index()
    {
        if ($error = $this->sendPermissionError('admin.ratio_report.index')) {
            return $error;
        }

        return view('admin.ratio_analysis.index');
    }
    public function ratio_analysis_select_profession()
    {
        if ($error = $this->sendPermissionError('admin.ratio_report.index')) {
            return $error;
        }
        return view('admin.ratio_analysis.select_profession');
    }
    public function ratio_analysis_financial_report()
    {
        if ($error = $this->sendPermissionError('admin.ratio_report.index')) {
            return $error;
        }
        return view('admin.ratio_analysis.financial_report');
    }
    public function ratio_analysis_final_report()
    {
        if ($error = $this->sendPermissionError('admin.ratio_report.index')) {
            return $error;
        }
        // activity()
        //     ->performedOn(new GeneralLedger())
        //     ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Profit Loss GST Excl Report'])
        //     ->log('Report > Profit Loss GST Excl Report > '.$client->fullname .' > '. $profession->name);
        return view('admin.ratio_analysis.final_report');
    }



    // consolidate_cash_basis
    public function consolidate_cash_basis_index()
    {
        if ($error = $this->sendPermissionError('admin.consolidated_report.index')) {
            return $error;
        }

        return view('admin.consolidate_cash_basis.index');
    }
    public function consolidate_cash_basis_gst_bas()
    {
        if ($error = $this->sendPermissionError('admin.consolidated_report.index')) {
            return $error;
        }
        return view('admin.consolidate_cash_basis.gst_bas');
    }
    public function consolidate_cash_basis_report()
    {
        if ($error = $this->sendPermissionError('admin.consolidated_report.index')) {
            return $error;
        }
        return view('admin.consolidate_cash_basis.report');
    }


    // comperative_financial_report
    public function comperative_financial_report_index()
    {
        if ($error = $this->sendPermissionError('admin.comperative_financial_report.index')) {
            return $error;
        }

        return view('admin.comperative_financial_report.index');
    }
    public function comperative_financial_report_select_profession()
    {
        if ($error = $this->sendPermissionError('admin.comperative_financial_report.index')) {
            return $error;
        }
        return view('admin.comperative_financial_report.select_profession');
    }
    public function comperative_financial_report_financial_report()
    {
        if ($error = $this->sendPermissionError('admin.comperative_financial_report.index')) {
            return $error;
        }
        return view('admin.comperative_financial_report.financial_report');
    }


    // Client payment
    public function client_payment_index()
    {
        if ($error = $this->sendPermissionError('admin.client_payment.index')) {
            return $error;
        }
        return view('admin.client_payment.index');
    }


    // fuel_tax_credit
    public function fuel_tax_credit_index()
    {
        return view('admin.fuel_tax_credit.index');
    }

    // tax_calculetor
    public function tax_calculator_index()
    {
        return view('admin.tax_calculator.index');
    }




    // wagesr
    public function wagesr_index()
    {
        return view('admin.wages.index');
    }
    public function wagesr_edit()
    {
        return view('admin.wages.edit');
    }



    // superannuation
    public function superannuation_index()
    {
        return view('admin.superannuation.index');
    }

    // leave
    public function leave_index()
    {
        return view('admin.leave.index');
    }

    // deducation
    public function deducation_index()
    {
        return view('admin.deducation.index');
    }

    // verify_accounts
    public function verify_accounts_index()
    {
        return view('admin.verify_accounts.index');
    }
    public function verify_accounts_select_profession()
    {
        return view('admin.verify_accounts.select_profession');
    }
    public function verify_accounts_general_ledger()
    {
        return view('admin.verify_accounts.general_ledger');
    }
    public function verify_accounts_final_report()
    {
        return view('admin.verify_accounts.final_report');
    }


    // fixed_accounts
    public function fixed_accounts_index()
    {
        return view('admin.fixed_accounts.index');
    }


    // period_lock
    public function period_lock_index()
    {
        return view('admin.period_lock.index');
    }
    public function period_lock_set_period_lock()
    {
        return view('admin.period_lock.set_period_lock');
    }


    // financial_year_close & data backup
    public function financial_year_close_index()
    {
        return view('admin.financial_year_close.index');
    }
    public function financial_year_close_select_profession()
    {
        return view('admin.financial_year_close.select_profession');
    }
    public function financial_year_close_select_year()
    {
        return view('admin.financial_year_close.select_year');
    }


    // payroll_year_close_index
    public function payroll_year_close_index()
    {
        return view('admin.payroll_year_close.index');
    }
    public function payroll_year_close_select_profession()
    {
        return view('admin.payroll_year_close.select_profession');
    }


    // data_restore
    public function data_restore_index()
    {
        return view('admin.data_restore.index');
    }
    public function data_restore_select_profession()
    {
        return view('admin.data_restore.select_profession');
    }
    public function data_restore_select_year()
    {
        return view('admin.data_restore.select_year');
    }


    // closed_year_report_financial
    public function closed_year_report_financial_index()
    {
        return view('admin.closed_year_report_financial.index');
    }
    public function closed_year_report_financial_select_profession()
    {
        return view('admin.closed_year_report_financial.select_profession');
    }
    public function closed_year_report_financial_select_year()
    {
        return view('admin.closed_year_report_financial.select_year');
    }

    // agent_audit
    public function agent_audit_index()
    {
        return view('admin.agent_audit.index');
    }
    public function agent_audit_agent_activity()
    {
        return view('admin.agent_audit.agent_activity');
    }

    // logging_audit
    public function logging_audit_index()
    {
        return view('admin.logging_audit.index');
    }
}
