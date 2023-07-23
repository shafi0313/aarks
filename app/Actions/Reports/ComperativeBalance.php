<?php

namespace App\Actions\Reports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ComperativeBalance extends Controller
{
    public function report(Request $request, $client, $profession)
    {
        $date     = makeBackendCompatibleDate($request->date);
        $retain_date = $date;
        $end_date = $date->format('Y-m-d');
        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $start_date     = $date->format('Y') . '-07-01';
            $pre_start_date = $date->format('Y') - 1 . '-07-01';
        } else {
            $start_date     = $date->format('Y') - 1 . '-07-01';
            $pre_start_date = $date->format('Y') - 2 . '-07-01';
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

        $retain = retain($client, $profession, $retain_date);

        $totalRetain = $retain ?? 0;

        $preRetain = GeneralLedger::select('balance_type', DB::raw("sum(balance) as totalRetain"))
            ->where('chart_id', 999999)
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '<', $pre_start_date)
            ->groupBy('chart_id')
            ->first();
        $totalPreRetain = $preRetain->totalRetain ?? 0;

        $totalPl    = pl($client, $profession, $date);
        $totalPrePl = accum_pl($client, Carbon::parse($start_date)->subDay());

        $preLedgers = GeneralLedger::where('date', '<', $start_date)
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)->get();
        return compact('accountCodeCategories', 'accountCodes', 'industryCategories', 'ledgers', 'preLedgers', 'date', 'client', 'totalRetain', 'totalPl', 'totalPreRetain', 'totalPrePl', 'profession');
    }
    public function consoleReport(Request $request, $path)
    {
        // $professions = $client->professions->pluck('id')->toArray();
        // ->whereIn('profession_id', $professions)
    }
}
