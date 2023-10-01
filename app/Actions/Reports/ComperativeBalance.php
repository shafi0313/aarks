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
        $end_date = $date->format('Y-m-d');

        // Pre Retain Date
        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $pre_retain_date_b = $date->format('Y') - 1 . '-07-01';
        } else {
            $pre_retain_date_b = $date->format('Y') - 2 . '-07-01';
        }
        $pre_retain_date = Carbon::createFromFormat('Y-m-d', $pre_retain_date_b);


        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $start_date     = $date->format('Y') . '-07-01';
            $pre_start_date = $date->format('Y') - 1 . '-07-01';
        } else {
            $start_date     = $date->format('Y') - 1 . '-07-01';
            $pre_start_date = $date->format('Y') - 2 . '-07-01';
        }
        // Current Retain Date
        $retain_date = $date;

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
                })->whereNull('parent_id')
                    ->orderBy('code', 'asc');
            }
        ]);
        $accountCodes = ClientAccountCode::select('id', 'name', 'code', 'gst_code', 'category_id', 'sub_category_id', 'additional_category_id', 'client_id', 'profession_id')
            ->where('profession_id', $profession->id)
            ->where('client_id', $client->id)
            ->where(function ($q) {
                $q->where('code', 'like', '5%')
                    ->orWhere('code', 'like', '9%');
            })->orderBy('code', 'asc')
            ->whereNotIn('code', [999998, 999999])
            ->get();

            $industryCategories    = $profession->industryCategories;
            $accountCodeCategories = $profession->accountCodeCategories;

        // Pre Ledgers
        $preLedgers = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '<', $start_date)
            ->whereNotIn('chart_id', [999998, 999999])
            ->get(ledgerSetVisible());

        $totalPrePl     = pl($client, $profession, Carbon::parse($start_date)->subDay());
        $totalPreRetain = retain($client, $profession, $pre_retain_date);

        // Current Ledgers
        $ledgers = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '<=', $end_date)
            ->whereNotIn('chart_id', [999998, 999999])
            ->get(ledgerSetVisible());

        $totalPl     = pl($client, $profession, $date);
        $totalRetain = retain($client, $profession, $retain_date);

        return compact('accountCodeCategories', 'accountCodes', 'industryCategories', 'ledgers', 'preLedgers', 'date', 'client', 'totalRetain', 'totalPl', 'totalPreRetain', 'totalPrePl', 'profession');
    }
    public function consoleReport(Request $request, $path)
    {
        // $professions = $client->professions->pluck('id')->toArray();
        // ->whereIn('profession_id', $professions)
    }
}
