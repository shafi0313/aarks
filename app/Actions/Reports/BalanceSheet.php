<?php

namespace App\Actions\Reports;

use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Models\AccountCodeCategory;
use App\Http\Controllers\Controller;

class BalanceSheet extends Controller
{
    public function report(Request $request, $client, $profession)
    {
        $date     = makeBackendCompatibleDate($request->date);
        $end_date = $date->format('Y-m-d');
        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $start_date = $date->format('Y') . '-07-01';
        } else {
            $start_date = $date->format('Y') - 1 . '-07-01';
        }

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
                    $q->where('code', 'like', '5%')
                        ->orWhere('code', 'like', '9%');
                })->whereNull('parent_id')->orderBy('code', 'asc');
            }
        ]);
        $accountCodes = ClientAccountCode::where('profession_id', $profession->id)
            ->where('client_id', $client->id)
            ->where(function ($q) {
                $q->where('code', 'like', '5%')
                    ->orWhere('code', 'like', '9%');
            })->orderBy('code', 'asc')->get();
        $industryCategories    = $profession->industryCategories;
        $accountCodeCategories = $profession->accountCodeCategories;

        $ledgers = GeneralLedger::where('date', '<=', $end_date)
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)->get();

        $retain   = retain($client, $profession, $date);;
        $plRetain = pl($client, $profession, $date);
        return compact('accountCodeCategories', 'accountCodes', 'industryCategories', 'ledgers', 'date', 'client', 'retain', 'plRetain', 'profession');
    }
    public function consoleReport(Request $request, $client)
    {
        $date     = makeBackendCompatibleDate($request->date);
        $end_date = $date->format('Y-m-d');
        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $start_date = $date->format('Y') . '-07-01';
        } else {
            $start_date = $date->format('Y') - 1 . '-07-01';
        }
        $professions = $client->professions->pluck('id')->toArray();
        $accountCodes = ClientAccountCode::where('client_id', $client->id)
            ->whereIn('profession_id', $professions)
            ->where(function ($q) {
                $q->where('code', 'like', '5%')
                    ->orWhere('code', 'like', '9%');
            })->orderBy('code', 'asc')->get();
        $accountCodeCategories = AccountCodeCategory::with('subCategory', 'industryCategories', 'subCategoryWithoutAdditional')
            ->where('type', 1)->where(function ($q) {
                $q->where('code', 'like', '5%')
                    ->orWhere('code', 'like', '9%');
            })->whereNull('parent_id')->orderBy('code', 'asc')->get();

        $ledgers = GeneralLedger::where('date', '<=', $end_date)
            ->where('client_id', $client->id)
            ->whereIn('profession_id', $professions)
            ->get();

        // $retain = GeneralLedger::select('balance_type', DB::raw("sum(balance) as totalRetain"))
        //     ->where('client_id', $client->id)
        //     ->where('chart_id', 999999)
        //     ->whereIn('profession_id', $professions)
        //     ->where('date', '<', $start_date)
        //     ->groupBy('chart_id')
        //     ->first();
        $retain   = console_retain($client, $date);
        $plRetain = accum_pl($client, $date);
        return compact('accountCodeCategories', 'accountCodes', 'ledgers', 'date', 'client', 'retain', 'plRetain');
    }
}
