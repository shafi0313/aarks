<?php

namespace App\Http\Controllers;

use App\Models\DepAssetName;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Actions\RetainEarning;
use App\Models\ClientAccountCode;
use App\Models\DisposeableLedger;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class AssetRolloverController extends Controller
{
    public function getAsset(Request $request)
    {
        // return $request;
        if ($request->ajax()) {
            $year = $request->year - 1;
            $rolloverAssets =  DepAssetName::where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)
                ->where('depreciation_id', $request->depreciation_id)
                ->where('is_rollover', 0)
                ->whereNull('disposal_date')
                ->where('year', $year)
                ->get(['id', 'asset_name']);
            // !Blade
            $opt = '<option value="">--Please Select Asset Name--</option>';
            foreach ($rolloverAssets as $asset) {
                $opt .= "<option value='{$asset->id}'>{$asset->asset_name}</option>";
            }
            return response()->json(['opt' => $opt]);
        }
        return redirect()->route('index');
    }

    public function post(Request $request)
    {
        $request->validate([
            "client_id"       => "required|integer",
            "profession_id"   => "required|integer",
            "depreciation_id" => "required|integer",
            "rollover_year"   => "required|integer",
            "rollover_asset"  => "required|integer",
        ]);
        DB::beginTransaction();
        $asset = DepAssetName::find($request->rollover_asset);
        // if (periodLock($asset->client_id, $asset->purchase_date)) {
        //     Alert::error('Your enter data period is locked, check administration');
        //     return back();
        // }
        $array = $asset->toArray();
        unset($array["id"]);
        unset($array["created_at"]);
        unset($array["updated_at"]);

        $array['year']          = $request->rollover_year;
        $array['rollover_year'] = $asset->year;
        $array['owdv_value']    = $owdv    = $asset->cwdv_value;
        $array['dep_amt']       = $dep_amt = $owdv * ($asset->dep_rate / 100);
        $array['cwdv_value']    = $owdv - $dep_amt;
        // $array['batch']         = $asset->client_id.$asset->profession_id.$asset->id;

        // Ledger Calculations
        $rollover_pre_year = $request->rollover_year - 1;
        $purch_date        = makeBackendCompatibleDate('01/07/' . $rollover_pre_year);
        $year              = $request->rollover_year;
        $purchDay          = $purch_date->format('d');

        $array['purchase_date']   = $asset->purchase_date->format('Y-m-d');
        $array['owdv_value_date'] = $asset->owdv_value_date->format('Y-m-d');

        $newAsset          = DepAssetName::create($array);

        $month    = 12;
        // $totalDay = 365 - $purchDay;
        $totalDay = 365;
        if ($year % 4 == 0) {
            $yearDay = $totalDay + 1;
        } else {
            $yearDay = $totalDay;
        }
        $ledger['narration']              = $asset->asset_name;
        $ledger['source']                 = "DEP";
        $ledger['loan']                   =
            $ledger['debit']                  =
            $ledger['credit']                 =
            $ledger['gst']                    =
            $ledger['client_account_code_id'] =
            $ledger['balance']                = 0;
        $ledger['client_id']              = $client_id     = $newAsset->client_id;
        $ledger['profession_id']          = $profession_id = $newAsset->profession_id;

        $tran_array = [];
        for ($t = 1; $t <= $month; $t++) {
            $tran_array[$t] = transaction_id('DEP');
        }

        $dep_amt = number_format($dep_amt / $yearDay, 4);
        $m       = $purch_date->format('m');
        $M       = 1;
        if ($newAsset->depreciation->dep_acc) {
            for ($i = 1; $i <= $month; $i++) {
                $mm = $m++;
                if ($mm >= 7 && $mm <= 12) {
                    $date = makeBackendCompatibleDate('01/' . $mm . '/' . ($year - 1));
                } else {
                    $mo = $mm > 12 ? $M++ : $mm;
                    $date = makeBackendCompatibleDate('01/' . $mo . '/' . $year);
                }
                $ledger['date'] = $date->endOfMonth();
                $ledger['transaction_id']         = $tran_array[$i];
                $ledger['balance_type']           = 1;
                $ledger['chart_id']               = $newAsset->depreciation->da_code->code;
                $ledger['client_account_code_id'] = $newAsset->depreciation->da_code->id;
                $ledger['credit']                 = 0;

                $new_dep_amt = $dep_amt * date('t', strtotime($ledger['date']));
                // if ($purch_date->format('m') == $date->format('m')) {
                //     $ledger['debit']  =
                //         $ledger['balance'] =
                //         $dep_amt * (date('t', strtotime($ledger['date'])) - $purchDay);
                // } else {
                $ledger['debit']   = abs($new_dep_amt);
                $ledger['balance'] = $new_dep_amt;
                // }

                $accm_dep_acc_ledger = GeneralLedger::create($ledger);
                DisposeableLedger::create([
                    'dep_asset_name_id' => $newAsset->id,
                    'general_ledger_id' => $accm_dep_acc_ledger->id,
                    'batch'             => $newAsset->batch,
                ]);

                // Retain Earning For each Transection
                RetainEarning::tranRetain($client_id, $profession_id, $ledger['transaction_id'], $ledger, ['DEP', 'DEP']);
            }
        }

        $m = $purch_date->format('m');
        $M = 1;
        if ($newAsset->depreciation->accm_dep_acc) {
            for ($i = 1; $i <= $month; $i++) {
                $mm = $m++;
                if ($mm >= 7 & $mm <= 12) {
                    $date = makeBackendCompatibleDate('01/' . $mm . '/' . ($year - 1));
                } else {
                    $mo = $mm > 12 ? $M++ : $mm;
                    $date = makeBackendCompatibleDate('01/' . $mo . '/' . $year);
                }
                $ledger['date'] = $date->endOfMonth();
                $ledger['transaction_id']         = $tran_array[$i];
                $ledger['chart_id']               = $newAsset->depreciation->ada_code->code;
                $ledger['client_account_code_id'] = $newAsset->depreciation->ada_code->id;
                $ledger['debit']                  = 0;
                $ledger['balance_type']           = 2;

                $new_dep_amt = $dep_amt * date('t', strtotime($ledger['date']));

                // if ($purch_date->format('m') == $date->format('m')) {
                //     $ledger['credit']  =
                //         $ledger['balance'] =
                //         $dep_amt * (date('t', strtotime($ledger['date'])) - $purch_date->format('d'));
                // } else {
                $ledger['credit']  = abs($new_dep_amt);
                $ledger['balance'] = $new_dep_amt;
                // }

                $accm_dep_acc_ledger = GeneralLedger::create($ledger);
                DisposeableLedger::create([
                    'dep_asset_name_id' => $newAsset->id,
                    'general_ledger_id' => $accm_dep_acc_ledger->id,
                    'batch'             => $newAsset->batch,
                ]);
            }
        }

        //RetailEarning Calculation
        RetainEarning::retain($client_id, $profession_id, $date, $ledger, ['DEP', 'DEP']);


        try {
            // return 'asd';
            $asset->update(['is_rollover' => true]);
            toast('Rollover successful', 'success');
            // if (notEmpty($asset->rollover_year)) {
            //     $asset->update(['rollover_year'=>$request->rollover_year]);
            // }
            DB::commit();
        } catch (\Exception $e) {
            toast('Opps! server side error!', 'error');
            DB::rollBack();
            // return $e->getMessage();
        }
        return back();
    }
    //reinstated_asset
    public function reinstatedAsset(Request $request)
    {
        $request->validate([
            "client_id"       => "required|integer",
            "profession_id"   => "required|integer",
            "depreciation_id" => "required|integer",
            "year"            => "required|integer",
        ]);
        if ($request->ajax()) {
            $year = $request->year;
            $rolloverAssets =  DepAssetName::where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)
                ->where('depreciation_id', $request->depreciation_id)
                ->whereNotNull('rollover_year')
                ->whereNull('disposal_date')
                ->where('year', $year)
                ->get(['id', 'asset_name']);
            // !Blade
            $opt = '<option value="">--Plase Select Asset Name--</option>';
            foreach ($rolloverAssets as $asset) {
                $opt .= "<option value='{$asset->id}'>{$asset->asset_name}</option>";
            }
            return response()->json(['opt' => $opt, 'status' => 200]);
        }
        return redirect()->route('index');
    }
    public function reinstatedPost(Request $request)
    {
        $asset = DepAssetName::findOrFail($request->reinstated_asset);
        $assetExest =  DepAssetName::where('client_id', $asset->client_id)
            ->where('profession_id', $asset->profession_id)
            ->where('depreciation_id', $asset->depreciation_id)
            ->whereNotNull('rollover_year')
            ->where('year', '>', $asset->year)->first();
        $preAsset =  DepAssetName::where('client_id', $asset->client_id)
            ->where('profession_id', $asset->profession_id)
            ->where('depreciation_id', $asset->depreciation_id)
            ->where('year', ($asset->year - 1))->first();
        if ($assetExest) {
            toast("You can't Reinstate this asset!", 'warning');
            return back();
        }

        $start_date = $request->reinstated_year - 1 . '-07-01';
        $end_date = $request->reinstated_year . '-06-30';

        $ledgers =  GeneralLedger::where('client_id', $asset->client_id)
            ->where('profession_id', $asset->profession_id)
            ->where('date', '>=', $start_date)
            ->where('date', '<=', $end_date)
            ->where('source', 'DEP');
        try {
            $asset->delete();
            $ledgers->delete();
            $preAsset->update(['is_rollover' => 0]);
            DisposeableLedger::where('dep_asset_name_id', $asset->id)->orWhere('batch', $asset->batch)->delete();
            toast('Successfully Reinstated Asset', 'success');
        } catch (\Exception $e) {
            toast('Opps! server side error!', 'error');
            return $e->getMessage();
        }
        return back();
    }
}
