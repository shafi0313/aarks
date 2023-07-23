<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\DepAssetName;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Actions\RetainEarning;
use App\Models\ClientAccountCode;
use App\Models\DisposeableLedger;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class AssetDesposalController extends Controller
{
    public function getDesposal(Request $request)
    {
        $list = '';
        $disposals = DepAssetName::where('client_id', $request->client_id)
            ->where('profession_id', $request->profession_id)
            ->where('depreciation_id', $request->dep_id)
            ->whereNotNull('year')->latest()
            ->get()->groupBy('batch');
        foreach ($disposals as $disposal) {
            $list .= view('admin.add_depreciation.disposal.disposal_list', [
                'get'      => 'list',
                'disposal' => $disposal->first()
            ]);
        }
        return response()->json(['data' => $list]);
    }
    public function getDesposalModal(Request $request)
    {
        $modal = '';
        $disposal = DepAssetName::find($request->id);
        $nonCurrents = ClientAccountCode::where('client_id', $disposal->client_id)
            ->where('profession_id', $disposal->profession_id)
            ->where('code', 'like', '56%')
            ->orderBy('code')->get(['id','name','code']);
        $assets = ClientAccountCode::where('client_id', $disposal->client_id)
            ->where('profession_id', $disposal->profession_id)
            ->where('code', 'like', '5%')
            ->orderBy('code')->get(['id','name','code']);
        $payable = ClientAccountCode::where('client_id', $disposal->client_id)
            ->where('profession_id', $disposal->profession_id)
            ->where('code', '912100')->first();
        // $incomes = ClientAccountCode::where('client_id', $disposal->client_id)
        //     ->where('profession_id', $disposal->profession_id)
        //     ->where('code', 'like', '1%')
        //     ->orderBy('code')->get();
        $modal .= view('admin.add_depreciation.disposal.disposal_list', [
            'get'         => 'modal',
            'disposal'    => $disposal,
            'nonCurrents' => $nonCurrents,
            'assets'      => $assets,
            'payable'     => $payable,
            // 'incomes'     => $incomes,
        ]);

        return response()->json(['modal' => $modal]);
    }
    public function getDesposalAmount(Request $request, DepAssetName $asset_name)
    {
        // return DepAssetName::findOrFail($request->asset);
        $year                 = $asset_name->year;
        $purchase_date        = Carbon::parse('01-07-' . $year-1);
        $purchase_date_format = $purchase_date->format('Y-m-d');

        $disposal_date        = makeBackendCompatibleDate($request->disposal_date);
        $disposal_date_format = $disposal_date->format('Y-m-d');
        $disposal_pre_date    = $disposal_date->subMonthWithoutOverflow()->format('Y-m-t');

        if ($disposal_date_format <= $purchase_date_format || $disposal_date_format > $year . "-06-30") {
            return response()->json(['status' => 500, 'message' => 'Disposal date is out of financial year']);
        }

        $disposeable_ledger_id = DisposeableLedger::where('batch', $asset_name->batch)->pluck('general_ledger_id')->toArray();

        $new_dep_amt  = GeneralLedger::whereIn('id', $disposeable_ledger_id)
        ->where('chart_id', $asset_name->depreciation->ada_code->code)
        ->where('date', '<=', $disposal_pre_date)->sum('balance');

        return response()->json(['status' => 200, 'dep_amt' => $new_dep_amt]);
    }
    public function postDesposalAasset(Request $request, DepAssetName $asset)
    {
        // return $request;
        $request->validate([
            "purchase_date"  => 'required|string',
            "disposal_date"  => 'required|string',
            "disposal_price" => 'required|string',
            "ac_code.*"      => 'required|string',
        ]);
        if (periodLock($asset->client_id, makeBackendCompatibleDate($request->purchase_date))) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        $purchase_date        = makeBackendCompatibleDate($request->purchase_date);
        $purchase_date_format = $purchase_date->format('Y-m-d');
        $disposal_date        = makeBackendCompatibleDate($request->disposal_date);
        $disposal_date_format = $disposal_date->format('Y-m-d');

        $disposeable_ledger_id = DisposeableLedger::where('id', $asset->id)->orWhere('batch', $asset->batch);
        DB::beginTransaction();

        $ledger['date']                   = $disposal_date_format;
        $ledger['narration']              = $asset->asset_name;
        $ledger['source']                 = "DEP";
        $ledger['transaction_id']         = $tran_id = transaction_id('DEP');
        $ledger['loan']                   =
        $ledger['debit']                  =
        $ledger['credit']                 =
        $ledger['gst']                    =
        $ledger['client_account_code_id'] =
        $ledger['balance']                = 0;
        $ledger['client_id']              = $client_id     = $asset->client_id;
        $ledger['profession_id']          = $profession_id = $asset->profession_id;

        $account_codes = ClientAccountCode::whereIn('id', $request->ac_code)->get();
        foreach ($request->ac_code as $i => $ac_code) {
            $account_code = $account_codes->where('id', $ac_code)->first();
            $ledger['chart_id']               = $account_code->code;
            $ledger['client_account_code_id'] = $account_code->id;
            $ledger['balance_type']           = $account_code->type;

            if ($account_code->type == 1) {
                $ledger['credit']       = 0;
                if ($request->debit[$i] > 0) {
                    $ledger['debit']   = $request->debit[$i];
                    $ledger['balance'] = $request->debit[$i];
                } else {
                    $ledger['credit']   = abs($request->credit[$i]);
                    $ledger['balance'] = - $request->credit[$i];
                }
            }

            if ($account_code->type == 2) {
                $ledger['debit']       = 0;
                if ($request->credit[$i] > 0) {
                    $ledger['credit']   = $request->credit[$i];
                    $ledger['balance'] = $request->credit[$i];
                } else {
                    $ledger['debit']   = abs($request->debit[$i]);
                    $ledger['balance'] = - $request->debit[$i];
                }
            }
            GeneralLedger::create($ledger);
        }
        //RetailEarning Calculation
        RetainEarning::retain($client_id, $profession_id, $disposal_date, $ledger, ['DEP', 'DEP']);
        // Retain Earning For each Transection
        RetainEarning::tranRetain($client_id, $profession_id, $tran_id, $ledger, ['DEP', 'DEP']);
        //RetailEarning Calculation End....


        // Delete Ledgers
        $disposeable_ledgers = GeneralLedger::whereIn('id', $disposeable_ledger_id->pluck('general_ledger_id'))
            ->where('date', '>', $disposal_date_format);
        GeneralLedger::whereIn('transaction_id', $disposeable_ledgers->pluck('transaction_id'))
            ->where('chart_id', 999998)->delete();
        $disposeable_ledgers->delete();
        $disposeable_ledger_id->delete();
        // End

        if (in_array($purchase_date->format('m'), range(1, 6))) {
            $start_year = $purchase_date->format('Y') - 1 . '-07-01';
            $end_year   = $purchase_date->format('Y') . '-06-30';
        } else {
            $start_year = $purchase_date->format('Y') . '-07-01';
            $end_year   = $purchase_date->format('Y') + 1 . '-06-30';
        }

        $inRetain   = GeneralLedger::where('client_id', $asset->client_id)
            ->where('profession_id', $asset->profession_id)
            ->where('date', '>=', $start_year)
            ->where('date', '<=', $end_year)
            ->where('chart_id', 'LIKE', '1%')
            ->where('source', 'DEP')
            ->get(['id', 'balance_type', 'balance']);
        $inRetainData = 0;
        foreach ($inRetain as $intr) {
            if ($intr->balance_type == 2 && $intr->balance > 0) {
                $inRetainData += abs($intr->balance);
            } else {
                $inRetainData -= abs($intr->balance);
            }
        }

        $exRetain = GeneralLedger::where('client_id', $asset->client_id)
            ->where('profession_id', $asset->profession_id)
            ->where('date', '>=', $start_year)
            ->where('date', '<=', $end_year)
            ->where('chart_id', 'LIKE', '2%')
            ->where('source', 'DEP')
            ->get(['id', 'balance_type', 'balance']);
        $exRetainData = 0;
        foreach ($exRetain as $intr) {
            if ($intr->balance_type == 1 && $intr->balance > 0) {
                $exRetainData += abs($intr->balance);
            } else {
                $exRetainData -= abs($intr->balance);
            }
        }

        $retainData = $inRetainData - $exRetainData;
        $retain['balance']                = $retainData;

        if ($inRetainData > $exRetainData) {
            $retain['credit'] = abs($retainData);
            $retain['debit']  = 0;
        } else {
            $retain['debit']  = abs($retainData);
            $retain['credit'] = 0;
        }

        $isRetain = GeneralLedger::where('client_id', $asset->client_id)
            ->where('profession_id', $asset->profession_id)
            ->where('date', '>=', $start_year)
            ->where('date', '<=', $end_year)
            ->where('chart_id', 999999)
            ->where('source', 'DEP')
            ->first();
        if ($isRetain) {
            $isRetain->update($retain);
        }


        $dep_amt = $request->dep_debit ?? $request->dep_credit;
        $asset->update([
            'is_rollover'    => 1,
            'dep_amt'        => $dep_amt,
            'disposal_date'  => $disposal_date_format,
            'disposal_value' => $request->disposal_price,
            'cwdv_value'     => $asset->owdv_value - $dep_amt,
        ]);

        // Insert New codeName

        try {
            DB::commit();
            Alert::success('Asset Disposal Successfully', 'Done');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
