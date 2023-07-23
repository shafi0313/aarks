<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class TestController extends Controller
{
    public function blank()
    {
        return view('admin.blank');
    }

    public function cash_periodic_index()
    {
        return view('admin.cash_periodic.index');
    }
    public function cash_periodic_b_a_report()
    {
        return view('admin.cash_periodic.b_a_report');
    }
    public function cash_periodic_gst_bas()
    {
        return view('admin.cash_periodic.gst_bas');
    }
    public function cash_periodic_balance()
    {
        return view('admin.cash_periodic.balance');
    }






    public function accrued_periodic_index()
    {
        return view('admin.accrued_periodic.index');
    }

    public function accrued_periodic_b_a_report()
    {
        return view('admin.accrued_periodic.b_a_report');
    }

    public function accrued_periodic_gst_bas()
    {
        return view('admin.accrued_periodic.gst_bas');
    }

    public function accrued_periodic_balance()
    {
        return view('admin.accrued_periodic.balance');
    }






    // prfit and loss GST Exclusic

    public function profit_loss_gst_excl_index()
    {
        return view('admin.profit_loss_gst_excl.index');
    }
    public function profit_loss_select_profession()
    {
        return view('admin.profit_loss_gst_excl.select_profession');
    }
    public function profit_loss_report_period()
    {
        return view('admin.profit_loss_gst_excl.report_period');
    }
    public function profit_loss_final_report()
    {
        return view('admin.profit_loss_gst_excl.final_report');
    }



    // prfit and loss GST Inlcusic
    public function profit_loss_gst_incl_index()
    {
        return view('admin.profit_loss_gst_incl.index');
    }
    public function profit_loss_gst_incl_select_profession()
    {
        return view('admin.profit_loss_gst_incl.select_profession');
    }
    public function profit_loss_gst_incl_report_period()
    {
        return view('admin.profit_loss_gst_incl.report_period');
    }
    public function profit_loss_gst_incl_final_report()
    {
        return view('admin.profit_loss_gst_incl.final_report');
    }


    // depreciation_report
    public function depreciation_report_index()
    {
        return view('admin.depreciation_report.index');
    }
    public function depreciation_select_profession()
    {
        return view('admin.depreciation_report.select_profession');
    }
    public function depreciation_report_financial_year()
    {
        return view('admin.depreciation_report.financial_year');
    }
    public function depreciation_report_final_report()
    {
        return view('admin.depreciation_report.final_report');
    }


    // balance_sheet
    public function balance_sheet_index()
    {
        return view('admin.balance_sheet.index');
    }
    public function balance_sheet_select_profession()
    {
        return view('admin.balance_sheet.select_profession');
    }
    public function balance_sheet_b_a_report()
    {
        return view('admin.balance_sheet.b_a_report');
    }
    public function balance_sheet_select_date()
    {
        return view('admin.balance_sheet.select_date');
    }
    public function balance_sheet_final_report()
    {
        return view('admin.balance_sheet.final_report');
    }


    // comperative_balance_sheet
    public function comperative_balance_sheet()
    {
        return view('admin.comperative_balance_sheet.index');
    }
    public function comperative_balance_sheet_select_profession()
    {
        return view('admin.comperative_balance_sheet.select_profession');
    }
    public function comperative_balance_sheet_select_date()
    {
        return view('admin.comperative_balance_sheet.select_date');
    }
    public function comperative_balance_sheet_final_report()
    {
        return view('admin.comperative_balance_sheet.final_report');
    }





    // ratio_analysis
    public function ratio_analysis_index()
    {
        return view('admin.ratio_analysis.index');
    }
    public function ratio_analysis_select_profession()
    {
        return view('admin.ratio_analysis.select_profession');
    }
    public function ratio_analysis_financial_report()
    {
        return view('admin.ratio_analysis.financial_report');
    }
    public function ratio_analysis_final_report()
    {
        return view('admin.ratio_analysis.final_report');
    }



    // consolidate_cash_basis
    public function consolidate_cash_basis_index()
    {
        return view('admin.consolidate_cash_basis.index');
    }
    public function consolidate_cash_basis_gst_bas()
    {
        return view('admin.consolidate_cash_basis.gst_bas');
    }
    public function consolidate_cash_basis_report()
    {
        return view('admin.consolidate_cash_basis.report');
    }



    // complete_financial_report
    public function complete_financial_report_index()
    {
        return view('admin.complete_financial_report.index');
    }
    public function complete_financial_report_select_profession()
    {
        return view('admin.complete_financial_report.select_profession');
    }
    public function complete_financial_report_financial_report()
    {
        return view('admin.complete_financial_report.financial_report');
    }
    public function complete_financial_report_final_report()
    {
        return view('admin.complete_financial_report.financialfinal_report');
    }



    // comperative_financial_report
    public function comperative_financial_report_index()
    {
        return view('admin.comperative_financial_report.index');
    }
    public function comperative_financial_report_select_profession()
    {
        return view('admin.comperative_financial_report.select_profession');
    }
    public function comperative_financial_report_financial_report()
    {
        return view('admin.comperative_financial_report.financial_report');
    }


    // Client payment
    public function client_payment_index()
    {
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


    // logging_audit
    public function logging_audit_index()
    {
        return view('admin.logging_audit.index');
    }
}
