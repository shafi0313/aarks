<?php

namespace App\Actions\Reports;

use Carbon\Carbon;
use App\Models\Depreciation;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Models\AccountCodeCategory;
use App\Http\Controllers\Controller;

class ConsoleFinancial extends Controller
{
    public function report(Request $request, $client)
    {
        $date = makeBackendCompatibleDate($request->date);
        $end_date = $date->format('Y-m-d');
        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $year           = $date->format('Y') + 1;
            $pre_year       = $date->format('Y') - 1;
            $start_date     = $date->format('Y') . '-07-01';
            $pre_start_date = $pre_year - 1 . '-07-01';
        } else {
            $year               = $date->format('Y');
            $pre_year           = $date->format('Y') - 1;
            $start_date         = $date->format('Y') - 1 . '-07-01';
            $pre_start_date     = $date->format('Y') - 2 . '-07-01';
        }
        $professions = $client->professions->pluck('id')->toArray();
        $data = [
            'year'           => $year,
            'date'           => $date,
            'start_date'     => $start_date,
            'end_date'       => $end_date,
            'pre_start_date' => $pre_start_date,
        ];

        $data['is_balance_sheet'] = $data['is_incomestatment_note'] = $data['is_details_balance_sheet'] = $data['is_trading_profit_loss'] = $data['is_trial_balance'] = $data['is_cash_flow_statement'] = $data['is_statement_of_receipts_and_payments'] = $data['is_statement_of_chanes_in_equity'] = $data['is_rental_income_statement'] = $data['is_retal_income_consolidated'] = $data['is_depreciation_report'] = $data['is_livestock_trading_statement'] = $data['is_notes_to_financial_statements'] = $data['is_business_analysis_five_yeartrading'] = $data['is_business_analysis_monthly'] = $data['is_business_analysis_five_yearpl'] = $data['is_business_analysis_pl_with_sales'] = $data['is_business_analysis_monthly_sales'] = $data['is_business_analysis_expenses_bank_account'] = $data['is_directors_report'] = $data['is_directors_declaration'] = $data['is_audit_report'] = $data['is_compilation_report'] = $data['is_contents'] = $data['is_depreciation'] = false;

        if ($request->has('balance_sheet')) {
            $data['is_balance_sheet'] = true;
            $data['balance_sheet'] = 'Balance Sheet';

            $data['bs_accountCodeCategories'] = AccountCodeCategory::with('subCategory', 'industryCategories', 'subCategoryWithoutAdditional')
                ->where('type', 1)->where(function ($q) {
                    $q->where('code', 'like', '5%')
                        ->orWhere('code', 'like', '9%');
                })->whereNull('parent_id')->orderBy('code', 'asc')->get();

            $data['bs_accountCodes'] = ClientAccountCode::where('client_id', $client->id)
                ->whereIn('profession_id', $professions)
                ->where(function ($q) {
                    $q->where('code', 'like', '5%')
                        ->orWhere('code', 'like', '9%');
                })->orderBy('code', 'asc')->get();

            $data['bs_ledgers'] = GeneralLedger::where('date', '<=', $end_date)
                ->where('client_id', $client->id)
                ->whereIn('profession_id', $professions)->get();

            $data['bs_retain']   = console_retain($client, $date);
            $data['bs_plRetain'] =  consolePL($client, $date);
        }
        
        if ($request->has('incomestatment_note')) {
            $data['is_incomestatment_note']  = true;
            $data['incomestatment_note']     = 'Income Statment Note';
            $data['IEaccountCodeCategories'] = AccountCodeCategory::with('subCategory', 'industryCategories')
                ->where('type', 1)->where(function ($q) {
                    $q->where('code', 'like', '1%')
                        ->orWhere('code', 'like', '2%');
                })
                ->get();
            $data['IEretain'] = GeneralLedger::select('*', DB::raw("sum(balance) as totalRetain"))
                ->where('date', '<', $start_date)
                ->where('client_id', $client->id)
                ->whereIn('profession_id', $professions)
                ->where('chart_id', 999999)
                ->groupBy('chart_id')
                ->first();
            $data['IECRetain'] = GeneralLedger::select('*', DB::raw("sum(balance) as CRetain"))
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client->id)
                ->whereIn('profession_id', $professions)
                ->where('chart_id', 999998)
                ->first();
        }

        if ($request->has('details_balance_sheet')) {
            $data['is_details_balance_sheet'] = true;
            $data['details_balance_sheet']    = 'Details Balance Sheet';
            if ($request->balance_sheet == '' && $request->balance_sheet != 1) {
                $data['bs_accountCodeCategories'] = AccountCodeCategory::with('subCategory', 'industryCategories', 'subCategoryWithoutAdditional')
                    ->where('type', 1)->where(function ($q) {
                        $q->where('code', 'like', '5%')
                            ->orWhere('code', 'like', '9%');
                    })->whereNull('parent_id')->orderBy('code', 'asc')->get();

                $data['bs_accountCodes'] = ClientAccountCode::where('client_id', $client->id)
                ->whereIn('profession_id', $professions)
                    ->where(function ($q) {
                        $q->where('code', 'like', '5%')
                            ->orWhere('code', 'like', '9%');
                    })->orderBy('code', 'asc')->get();

                $data['bs_ledgers'] = GeneralLedger::where('date', '<=', $end_date)
                    ->where('client_id', $client->id)
                    ->whereIn('profession_id', $professions)->get();


                $data['bs_retain']   = console_retain($client, $date);
                $data['bs_plRetain'] = consolePL($client, $date);
            }
        }

        if ($request->has('trading_profit_loss')) {
            $data['is_trading_profit_loss']    = true;
            $data['trading_profit_loss']       = 'Trading Profit Loss';

            $data['tpl_ledgers'] = GeneralLedger::select('*', DB::raw("sum(balance) as
            balance ,sum(credit) as credit, sum(debit) as debit"))
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client->id)
                ->whereIn('profession_id', $professions)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
            $retain = GeneralLedger::select('balance_type', DB::raw("sum(balance) as totalRetain"))
                ->where('chart_id', 999999)
                ->where('client_id', $client->id)
                ->whereIn('profession_id', $professions)
                ->where('date', '<', $start_date)
                ->groupBy('chart_id')
                ->first();
            $data["tpl_totalRetain"] = $retain->totalRetain ?? 0;

            $data["tpl_totalPl"] = consolePL($client, $date);


            // return
            $data['tpl_preLedgers'] = GeneralLedger::select('*', DB::raw("sum(balance) as
            balance, sum(credit) as credit,sum(debit) as debit"))
                ->where('date', '>=', $pre_start_date)
                ->where('date', '<', $start_date)
                ->where('client_id', $client->id)
                ->whereIn('profession_id', $professions)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
            $preRetain = GeneralLedger::select('balance_type', DB::raw("sum(balance) as totalRetain"))
                ->where('chart_id', 999999)
                ->where('client_id', $client->id)
                ->whereIn('profession_id', $professions)

                ->where('date', '<', $pre_start_date)
                ->groupBy('chart_id')
                ->first();
            $data["tpl_totalPreRetain"] = $preRetain->totalRetain ?? 0;

            $data["tpl_totalPrePl"] =  consolePL($client, Carbon::parse($start_date)->subDays(1));

            $data["tpl_accountCodes"] = ClientAccountCode::where('client_id', $client->id)
            ->whereIn('profession_id', $professions)
                ->where(function ($q) {
                    $q->where('code', 'like', '1%')
                        ->orWhere('code', 'like', '2%');
                })->orderBy('code', 'asc')->get();

            $data["tpl_accountCodeCategories"] = AccountCodeCategory::with('subCategory', 'industryCategories', 'subCategoryWithoutAdditional')
                ->where('type', 1)->where(function ($q) {
                    $q->where('code', 'like', '1%')
                        ->orWhere('code', 'like', '2%');
                })->whereNull('parent_id')->orderBy('code', 'asc')->get();
        }
        if ($request->has('trial_balance')) {
            $data['is_trial_balance'] = true;
            $data['trial_balance'] = 'Trial Balance';
            // return $start_date;
            $start_dm  = $date->format('dm');
            $last_year = $date->format('Y') + 1 . '-06-30';
            $client_id = $client->id;
            if ($start_dm == '0107') {
                $data['trial_ledgers'] = GeneralLedger::select('*', DB::raw("sum(balance) as trail_balance"))
                    ->where('client_id', $client_id)
                    ->whereIn('profession_id', $professions)
                    ->where('date', '<=', $end_date)
                    ->where('chart_id', '!=', 999999)
                    ->where('chart_id', '!=', 999998)
                    ->where('chart_id', 'not like', '1%')
                    ->where('chart_id', 'not like', '2%')
                    ->groupBy('chart_id')
                    ->orderBy('chart_id')
                    ->get();
            } else {
                $data['trial_ledgers'] = GeneralLedger::select('*', DB::raw("sum(balance) as trail_balance"))
                    ->where('client_id', $client_id)
                    ->whereIn('profession_id', $professions)
                    ->where(function ($q) use ($start_date, $end_date) {
                        $q->where(function ($oneTwo) use ($start_date, $end_date) {
                            $oneTwo->where('date', '>=', $start_date)
                                ->where('date', '<=', $end_date)
                                ->where(function ($q) {
                                    $q->where('chart_id', 'like', '1%')
                                        ->orWhere('chart_id', 'like', '2%');
                                });
                        })
                            ->orWhere(function ($fiveNine) use ($end_date) {
                                $fiveNine->where('date', '<=', $end_date)
                                    ->where(function ($q) {
                                        $q->where('chart_id', 'like', '5%')
                                            ->orWhere('chart_id', 'like', '9%');
                                    });
                            });
                    })
                    ->where('chart_id', '!=', 999999)
                    ->where('chart_id', '!=', 999998)
                    ->groupBy('chart_id')
                    ->get();
            }
            $data['trial_retains'] = GeneralLedger::select('*', DB::raw("sum(balance) as totalRetain"))
                ->where('client_id', $client_id)
                ->whereIn('profession_id', $professions)
                ->where('date', '<', $start_date)
                ->where('chart_id', 999999)
                ->groupBy('chart_id')
                ->first();
            $data['trial_CRetains'] =  consolePL($client, $date);

            $data['trial_codes'] = ClientAccountCode::where('client_id', $client->id)
            ->whereIn('profession_id', $professions)
                ->orderBy('code')->get();
        }
        if ($request->has('cash_flow_statement')) {
            $data['is_cash_flow_statement'] = true;
            $data['cash_flow_statement'] = 'STATEMENT OF CASH FLOW';
            $bst_inp = GeneralLedger::where('client_id', $client->id)
            ->whereIn('profession_id', $professions)
                ->where('chart_id', 'like', "1%")
                ->where('chart_id', '!=', "551800")
                ->whereIn('source', ['BST', 'INP'])
                ->where('date', '<=', $end_date)
                ->select('*', DB::raw("sum(balance) as balance, sum(credit) as credit, sum(debit) as debit"))
                ->first();
            $pin = GeneralLedger::where('client_id', $client->id)
            ->whereIn('profession_id', $professions)
                ->where('chart_id', '!=', "551800")
                ->where('source', 'PIN')
                ->where('date', '<=', $end_date)
                ->select('*', DB::raw("sum(balance) as balance, sum(credit) as credit, sum(debit) as debit"))
                ->first();
            $data['cfs_receipt'] = $bst_inp->debit + $pin->debit;

            $data['cfs_ledger'] = GeneralLedger::where('client_id', $client->id)
            ->whereIn('profession_id', $professions)
                ->where('chart_id', 'like', "551%")
                ->where('chart_id', '!=', "551800")
                ->whereIn('source', ['PIN', 'INP'])
                ->where('date', '<=', $end_date)
                ->select('*', DB::raw("sum(balance) as balance, sum(credit) as credit, sum(debit) as debit"))
                ->first();
        }
        if ($request->has('statement_of_receipts_and_payments')) {
            // return $financial_year;
            $data['is_statement_of_receipts_and_payments'] = true;
            $data['statement_of_receipts_and_payments'] = 'Statement of receipts and payments';
            $data['srp_opening_ledger'] = GeneralLedger::where('client_id', $client->id)
            ->whereIn('profession_id', $professions)
                ->where('chart_id', 'like', "551%")
                ->where('date', '<=', $pre_year . '-06-30')
                ->select('*', DB::raw("sum(balance) as balance, sum(credit) as credit, sum(debit) as debit"))
                ->first();
            $data['srp_ledger'] = GeneralLedger::where('client_id', $client->id)
            ->whereIn('profession_id', $professions)
                ->where('chart_id', 'like', "551%")
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->select('*', DB::raw("sum(balance) as balance, sum(credit) as credit, sum(debit) as debit"))
                ->first();
        }
        if ($request->has('depreciation')) {
            $data['is_depreciation'] = true;
            $data['year']            = $year;
            $data['depreciation']    = 'Depreciation Report';
            $data['depreciations'] = Depreciation::with(['asset_names'])
                ->where('client_id', $client->id)
                ->whereIn('profession_id', $professions)
                ->get();
        }
        if ($request->has('directors_report')) {
            $totalIncome = GeneralLedger::where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client->id)

                ->select(DB::raw("sum(balance) as balance, sum(credit) as credit, sum(debit) as debit"))
                ->where('chart_id', 'like', "1%")
                ->first();
            $totalExpence = GeneralLedger::where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client->id)
                ->whereIn('profession_id', $professions)
                ->select(DB::raw("sum(balance) as balance, sum(credit) as credit, sum(debit) as debit"))
                ->where('chart_id', 'like', "2%")
                ->first();
            $data['is_directors_report'] = true;
            $data['directors_pl']        = abs($totalIncome->balance) - abs($totalExpence->balance);
            $data['directors_report']    = 'Directors Report';
        }
        if ($request->has('directors_declaration')) {
            $data['is_directors_declaration'] = true;
            $data['directors_declaration'] = 'Directors Declaration';
        }
        if ($request->has('notes_to_financial_statements')) {
            $data['is_notes_to_financial_statements'] = true;
            $data['notes_to_financial_statements'] = 'Notes to the Financial Statements';
        }
        if ($request->has('audit_report')) {
            $data['is_audit_report'] = true;
            $data['audit_report'] = 'Audit Report';
        }
        if ($request->has('compilation_report')) {
            $data['is_compilation_report'] = true;
            $data['compilation_report'] = 'Compilation Report';
        }
        return compact(['data', 'client', 'date', 'start_date', 'pre_start_date', 'end_date']);
    }
    public function consoleReport(Request $request, $path)
    {
    }
}
