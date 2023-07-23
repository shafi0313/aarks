<?php

namespace App\Actions\Reports;

use App\Models\Client;
use App\Models\Profession;
use App\Models\Depreciation;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Models\AccountCodeCategory;
use App\Http\Controllers\Controller;

class CompleteFinancialTFAction extends Controller
{
    public function report(Request $request, Client $client, Profession $profession)
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
                })->whereNull('parent_id')->orderBy('code', 'asc')
                ->get();

            $data['bs_accountCodes'] = ClientAccountCode::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where(function ($q) {
                    $q->where('code', 'like', '5%')
                        ->orWhere('code', 'like', '9%');
                })->orderBy('code', 'asc')->get();

            $data['bs_ledgers'] = GeneralLedger::where('date', '<=', $end_date)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->get();

            // Calculate total liabilities
            $get_total_liabilities = GeneralLedger::with('client_account_code')
                ->where('date', '<=', $end_date)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where(function ($q) {
                    $q->where('chart_id', 'like', '91%')
                        ->orWhere('chart_id', 'like', '954%');
                })
                ->get();

            $total_liability = 0;
            foreach ($get_total_liabilities as $get_total_liability) {
                $balance = $get_total_liability->balance;
                $balance_type = $get_total_liability->balance_type;
                $account_type = $get_total_liability->client_account_code->type;

                if ($account_type == 1) {
                    $total_liability += ($balance_type == 1) ? -$balance : $balance;
                } else {
                    $total_liability += ($balance_type == 2) ? $balance : -$balance;
                }
            }

            $data['total_liability'] = $total_liability;

            // Calculate total assets
            $get_total_assets = GeneralLedger::with('client_account_code')
                ->where('date', '<=', $end_date)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('chart_id', 'like', '5%')
                ->get();

            $total_asset = 0;
            foreach ($get_total_assets as $get_total_asset) {
                $balance = $get_total_asset->balance;
                $balance_type = $get_total_asset->balance_type;
                $account_type = $get_total_asset->client_account_code->type;

                if ($account_type == 1) {
                    $total_asset += ($balance_type == 1) ? $balance : -$balance;
                } else {
                    $total_asset += ($balance_type == 2) ? -$balance : $balance;
                }
            }

            $data['total_asset'] = $total_asset;
            $data['bs_retain']   = retain($client, $profession, $date);
            $data['bs_plRetain'] = pl($client, $profession, $date);
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
                ->where('profession_id', $profession->id)
                ->where('chart_id', 999999)
                ->groupBy('chart_id')
                ->first();
            $data['IECRetain'] = GeneralLedger::select('*', DB::raw("sum(balance) as CRetain"))
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('chart_id', 999998)
                ->first();
        }
        if ($request->has('details_balance_sheet')) {
            $data['is_details_balance_sheet'] = true;
            $data['details_balance_sheet'] = 'Details Balance Sheet';
            if ($request->balance_sheet == '' && $request->balance_sheet != 1) {
                $data['bs_accountCodeCategories'] = AccountCodeCategory::with('subCategory', 'industryCategories', 'subCategoryWithoutAdditional')
                    ->where('type', 1)->where(function ($q) {
                        $q->where('code', 'like', '5%')
                            ->orWhere('code', 'like', '9%');
                    })->whereNull('parent_id')->orderBy('code', 'asc')->get();

                $data['bs_accountCodes'] = ClientAccountCode::where('client_id', $client->id)
                    ->where('profession_id', $profession->id)
                    ->where(function ($q) {
                        $q->where('code', 'like', '5%')
                            ->orWhere('code', 'like', '9%');
                    })->orderBy('code', 'asc')->get();

                $data['bs_ledgers'] = GeneralLedger::where('date', '<=', $end_date)
                    ->where('client_id', $client->id)->where('profession_id', $profession->id)->get();

                // $data['bs_retain'] = GeneralLedger::select('balance_type', DB::raw("sum(balance) as totalRetain"))
                //     ->where('chart_id', 999999)
                //     ->where('client_id', $client->id)
                //     ->where('profession_id', $profession->id)
                //     ->where('date', '<', $start_date)
                //     ->groupBy('chart_id')
                //     ->first();
                // $data['bs_plRetain'] = pl($client, $profession, $date);
                $data['bs_retain'] = retain($client, $profession, $date);
                $data['bs_plRetain'] = pl($client, $profession, $date);
            }
        }
        if ($request->has('trading_profit_loss')) {
            $data['is_trading_profit_loss']    = true;
            $data['trading_profit_loss']       = 'Trading Profit Loss';

            $data['tpl_ledgers'] = GeneralLedger::select('*', DB::raw("sum(balance) as
            balance, sum(credit) as credit,sum(debit) as debit"))
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
            $data['tpl_preLedgers'] = GeneralLedger::select('*', DB::raw("sum(balance) as
            balance, sum(credit) as credit,sum(debit) as debit"))
                ->where('date', '>=', $pre_start_date)
                ->where('date', '<=', $start_date)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
            $industry_categories = $profession->industryCategories->pluck('id')->toArray();
            $profession->load([
                'accountCodeCategories' => function ($query) use ($industry_categories) {
                    return $query->with([
                        'subCategoryWithoutAdditional' => function ($sub_category_query) use ($industry_categories) {
                            return $sub_category_query->whereHas('industryCategories', function ($industry_category_query) use ($industry_categories) {
                                return $industry_category_query->whereIn('industry_category_id', $industry_categories);
                            })->with([
                                'additionalCategory' => function ($additional_category_query) use ($industry_categories) {
                                    return $additional_category_query
                                        ->whereHas('industryCategories', function ($industry_category_query) use ($industry_categories) {
                                            return $industry_category_query->whereIn('industry_category_id', $industry_categories);
                                        });
                                }
                            ])->orderBy('code', 'asc');
                        }
                    ])->where('type', 1)->where(function ($q) {
                        $q->where('code', 'like', '1%')
                            ->orWhere('code', 'like', '2%');
                    })->whereNull('parent_id')->orderBy('code', 'asc');
                }
            ]);
            $data["tpl_accountCodes"] = ClientAccountCode::where('profession_id', $profession->id)
                ->where('client_id', $client->id)
                ->where(function ($q) {
                    $q->where('code', 'like', '1%')
                        ->orWhere('code', 'like', '2%');
                })->orderBy('code', 'asc')->get();
            $industryCategories    = $profession->industryCategories;
            $data["tpl_accountCodeCategories"] = $profession->accountCodeCategories;

            $retain = GeneralLedger::select('balance_type', DB::raw("sum(balance) as totalRetain"))
                ->where('chart_id', 999999)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('date', '<', $start_date)
                ->groupBy('chart_id')
                ->first();
            $data["tpl_totalRetain"] = $retain->totalRetain ?? 0;
            $plRetain = GeneralLedger::select('balance_type', DB::raw("sum(balance) as totalPl"))
                ->where('chart_id', 999998)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->first();
            $data["tpl_totalPl"] = $plRetain->totalPl ?? 0;

            $preRetain = GeneralLedger::select('balance_type', DB::raw("sum(balance) as totalRetain"))
                ->where('chart_id', 999999)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('date', '<', $pre_start_date)
                ->groupBy('chart_id')
                ->first();
            $data["tpl_totalPreRetain"] = $preRetain->totalRetain ?? 0;
            $prePlRetain = GeneralLedger::select('balance_type', DB::raw("sum(balance) as totalPl"))
                ->where('chart_id', 999998)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('date', '>=', $pre_start_date)
                ->where('date', '<=', $start_date)
                ->first();
            $data["tpl_totalPrePl"] = $prePlRetain->totalPl ?? 0;
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
                    ->where('profession_id', $profession->id)
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
                    ->where('profession_id', $profession->id)
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
                ->where('profession_id', $profession->id)
                ->where('date', '<', $start_date)
                ->where('chart_id', 999999)
                ->groupBy('chart_id')
                ->first();
            $data['trial_CRetains'] = pl($client, $profession, $date);

            $data['trial_codes'] = ClientAccountCode::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->orderBy('code')->get();
        }
        if ($request->has('cash_flow_statement')) {
            $data['is_cash_flow_statement'] = true;
            $data['cash_flow_statement'] = 'STATEMENT OF CASH FLOW';
            $bst_inp = GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('chart_id', 'like', "1%")
                ->where('chart_id', '!=', "551800")
                ->whereIn('source', ['BST', 'INP'])
                ->where('date', '<=', $end_date)
                ->select('*', DB::raw("sum(balance) as balance, sum(credit) as credit, sum(debit) as debit"))
                ->first();
            $pin = GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('chart_id', '!=', "551800")
                ->where('source', 'PIN')
                ->where('date', '<=', $end_date)
                ->select('*', DB::raw("sum(balance) as balance, sum(credit) as credit, sum(debit) as debit"))
                ->first();
            $data['cfs_receipt'] = $bst_inp->debit + $pin->debit;

            $data['cfs_ledger'] = GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('chart_id', 'like', "551%")
                ->where('chart_id', '!=', "551800")
                ->whereIn('source', ['PIN', 'INP'])
                ->where('date', '<=', $end_date)
                ->select('*', DB::raw("sum(balance) as balance, sum(credit) as credit, sum(debit) as debit"))
                ->first();

            $data['cfs_income_cr'] = GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where(function ($q) {
                    $q->where('chart_id', 'like', "1%")
                        ->orWhere('chart_id', "552100");
                })
                // ->where('chart_id', 'like', "1%")
                // ->Where('chart_id', 'like', "552100%")
                ->where('source', '!=', 'INV')
                ->where('date', '<=', $end_date)
                ->select('*', DB::raw("sum(credit) as credit"))
                ->first();

            $data['cfs_expense_dr'] = GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('chart_id', 'like', "2%")
                ->whereNotIn('source', ['INV', 'PBP'])
                // ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->select('*', DB::raw("sum(debit) as debit"))
                ->first();

            // $data['cfs_debtors_cr'] = GeneralLedger::where('client_id', $client->id)
            //     ->where('profession_id', $profession->id)
            // ->where(function($q){
            //     $q->where('chart_id', 'like', "1%")
            //     ->orWhere('chart_id', 'like', "552100%");
            // })
            // ->where('chart_id', 'like', "1%")
            // ->Where('chart_id', "552100")
            // ->where('source', 'PIN')
            // ->where('date', '<=', $end_date)
            // ->select('*', DB::raw("sum(debit) as debit"))
            // ->first();

            // $data['cfs_liquid_assets_dr'] = GeneralLedger::where('client_id', $client->id)
            //     ->where('profession_id', $profession->id)
            //     ->where('chart_id', 'like', "551%")
            //     ->where('source', '!=', 'INV')
            //     // ->where('date', '>=', $start_date)
            //     ->where('date', '<=', $end_date)
            //     ->select('*', DB::raw("sum(debit) as debit"))
            //     ->first();



            //  dd($data['cfs_income_cr'], $data['cfs_debtors_cr'], $data['cfs_liquid_assets_dr']);
        }
        if ($request->has('statement_of_receipts_and_payments')) {
            // return $financial_year;
            $data['is_statement_of_receipts_and_payments'] = true;
            $data['statement_of_receipts_and_payments'] = 'Statement of receipts and payments';
            $data['srp_opening_ledger'] = GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('chart_id', 'like', "551%")
                ->where('date', '<=', $pre_year . '-06-30')
                ->select('*', DB::raw("sum(balance) as balance, sum(credit) as credit, sum(debit) as debit"))
                ->first();
            $data['srp_ledger'] = GeneralLedger::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('chart_id', 'like', "551%")
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->select('*', DB::raw("sum(balance) as balance, sum(credit) as credit, sum(debit) as debit"))
                ->first();
        }
        if ($request->has('depreciation')) {
            $data['is_depreciation'] = true;
            $data['depreciation']    = 'Depreciation Report';
            $data['depreciations']   = Depreciation::with(['asset_names'])
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->get();
        }
        if ($request->has('directors_report')) {
            $totalIncome = GeneralLedger::where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->select(DB::raw("sum(balance) as balance, sum(credit) as credit, sum(debit) as debit"))
                ->where('chart_id', 'like', "1%")
                ->first();
            $totalExpence = GeneralLedger::where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('client_id', $client->id)
                ->where('profession_id', $profession->id)
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
        return compact(['data', 'client', 'profession', 'date', 'start_date', 'pre_start_date', 'end_date']);
    }
    public function consoleReport(Request $request, $path)
    {
    }
}
