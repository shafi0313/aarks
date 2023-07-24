<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use App\Models\DepAssetName;
use App\Models\Depreciation;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Actions\RetainEarning;
use App\Models\ClientAccountCode;
use App\Models\DisposeableLedger;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AddDepreciationController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.depreciation.index')) {
            return $error;
        }

        $clients = getClientsWithPayment();
        return view('admin.add_depreciation.client', compact('clients'));
    }
    public function profession($id)
    {
        if ($error = $this->sendPermissionError('admin.depreciation.index')) {
            return $error;
        }

        $client = Client::with('professions')->findOrFail($id);
        return view('admin.add_depreciation.profession', compact('client'));
    }

    // ASSET GROUP
    public function assetGroupIndex(Client $client, Profession $profession)
    {
        // return $profession;
        if ($error = $this->sendPermissionError('admin.depreciation.edit')) {
            return $error;
        }

        $dep_accs = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', '>=', '243700')
            ->where('code', '<=', '243999')->get();
        $accm_dep_accs = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', 'like', '5%')->get();
        return view('admin.add_depreciation.input', compact('client', 'profession', 'dep_accs', 'accm_dep_accs'));
    }
    public function assetGroupStore(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.depreciation.create')) {
            return $error;
        }

        $data = $this->validate($request, [
            'asset_group'   => 'required|string',
            'dep_acc'       => 'required|string',
            'accm_dep_acc'  => 'required|string',
            'client_id'     => 'required|numeric',
            'profession_id' => 'required|numeric',
        ]);
        Depreciation::create($data);
        return json_encode(['data' => $data, 'status' => '200']);
    }
    public function assetGroupRead(Request $request)
    {
        $inputs = Depreciation::where('client_id', $request->client_id)
            ->where('profession_id', $request->profession_id)->get();
        $html = '';
        $html .= '<thead>';
        $html .= '    <tr class="bg-primary text-light">';
        $html .= '        <th style="text-align:center;">Group Asset Name</th>';
        $html .= '        <th style="text-align:center;">Depreciation account</th>';
        $html .= '        <th style="text-align:center;">Accumaleted Deprection Account</th>';
        $html .= '        <th style="text-align:center;">Enter Asset Name</th>';
        $html .= '    </tr>';
        $html .= '</thead>';

        if ($inputs->count() > 0) {
            foreach ($inputs as $input) {
                $html .= '<tr id="input_row_' . $input->id . '">';
                $html .= '<td>' . $input->asset_group  . '</td>';
                $html .= '<td>' . $input->da_code->name . '</td>';
                $html .= '<td>' . $input->ada_code->name . '</td>';
                $html .= '<td style="text-align:center">';
                $html .= '<a data-id="' . $input->id . '" class="red btn btn-info btn-sm" id="update" href="' . route('depreciation.name.index', $input->id) . '">Add/Edit Asset</a>';
                $html .= '</td>';
                $html .= '</tr>';
            }
            return json_encode(['status' => 'success', 'html' => $html]);
        } else {
            $html .= '<tr colspan="">';
            $html .= '<td>SORRY NO DATA FOUND!</td>';
            $html .= '<tr>';
            return json_encode(['status' => 'danger', 'html' => $html]);
        }
    }
    public function assetGroupEdit(Request $request)
    {
        $assetName = Depreciation::findOrFail($request->id);
        return json_encode(['status' => 'success', 'data' => $assetName]);
    }
    public function assetGroupUpdate(Request $request)
    {
        $data = $this->validate($request, [
            'asset_group'  => 'required|string',
            'dep_acc'      => 'required|string',
            'accm_dep_acc' => 'required|string',
        ]);
        Depreciation::findOrFail($request->id)->update($data);
        return json_encode(['data' => $data, 'status' => '200']);
    }
    // END ASSET GROUP

    // ASSET NAME
    public function assetNameIndex(Depreciation $dep)
    {
        $periods = Period::where('client_id', $dep->client_id)
            ->where('profession_id', $dep->profession_id)
            ->orderBy('year')
            ->groupBy('year')
            ->get();
        return view('admin.add_depreciation.add_asset', compact('dep', 'periods'/* , 'dep_assets' */));
    }
    public function assetNameStore(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.depreciation.create')) {
            return $error;
        }
        // return $request;
        $this->validate($request, [
            'asset_name' => 'required'
        ]);

        $data = [
            'batch'           => transaction_id('', 8),
            'asset_name'      => $request->get('asset_name'),
            'depreciation_id' => $request->get('dep_id'),
            'client_id'       => $request->get('client_id'),
            'profession_id'   => $request->get('profession_id'),
        ];
        DepAssetName::create($data);
        return json_encode(['status' => 'success', 'data' => $data]);
    }
    public function assetNameRead(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.depreciation.index')) {
            return $error;
        }

        $inputs = DepAssetName::where('depreciation_id', $request->dep_id)
            ->where('status', 1)->latest()
            ->get()->groupBy('batch');

        if ($inputs->count() > 0) {
            $active_assets = DepAssetName::where('depreciation_id', $request->dep_id)->whereNull('year')->get();
            $old_assets    = DepAssetName::where('depreciation_id', $request->dep_id)
                ->whereNotNull('year')
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->get();

            $asset_name = view('admin.add_depreciation.ajax', ['inputs' => $inputs, 'type' => 'asset_name'])->render();

            $active_asset = view('admin.add_depreciation.ajax', ['active_assets' => $active_assets, 'type' => 'active_asset_name'])->render();

            $old_asset = view('admin.add_depreciation.ajax', ['old_assets' => $old_assets, 'type' => 'old_asset_name'])->render();

            return response()->json(['status' => 'success', 'html' => $asset_name, 'dep_asset_name' => $active_asset, 'old_asset_name' => $old_asset]);
        } else {
            $asset_name = view('admin.add_depreciation.ajax', ['type' => 'error'])->render();
            return response()->json(['status' => 'danger', 'html' => $asset_name]);
        }
    }
    public function assetNameEdit(Request $request)
    {
        $assetName = DepAssetName::findOrFail($request->id);
        return json_encode(['status' => 'success', 'data' => $assetName]);
    }
    public function assetNameUpdate(Request $request)
    {
        $assetName = DepAssetName::findOrFail($request->id);
        $assetName->update(['asset_name' => $request->asset_name]);
        return json_encode(['status' => 'success']);
    }

    public function activeAssetUpdate(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.depreciation.edit')) {
            return $error;
        }
        $request->validate([
            "financial_year"           => "required|string",
            "purchase_rate"            => "required|string",
            "purchasedate"             => "required|date_format:d/m/Y",
            "purchaseprince"           => "required|string",
            "depreciation_rate"        => "required|string",
            "actual_cwdv"              => "nullable|string",
            "actual_depreciation"      => "nullable|string",
            "actual_owdv"              => "nullable|string",
            "assessable"               => "nullable|string",
            "balancing_out"            => "nullable|string",
            "balancingin"              => "nullable|string",
            "clientid"                 => "nullable|string",
            "deductible"               => "nullable|string",
            "dep_grupid"               => "nullable|string",
            "dep_subgrup"              => "nullable|string",
            "depreciation_method"      => "nullable|string",
            "disposal_date"            => "nullable|string",
            "disposal_price"           => "nullable|string",
            "effectivelife"            => "nullable|string",
            "net_depreciation"         => "nullable|string",
            "net_shareof_depreciation" => "nullable|string",
            "originalvalue"            => "nullable|string",
            "private_use"              => "nullable|string",
            "undeducted_cwdv"          => "nullable|string",
            "undeducted_depreciation"  => "nullable|string",
            "undeducted_owdv"          => "nullable|string",
        ]);

        $purch_date = makeBackendCompatibleDate($request->purchasedate);

        // if (periodLock($request->clientid, $purch_date)) {
        //     return response()->json('Your enter data period is locked, check administration', 403);
        // }

        $year     = $request->financial_year;
        $pdymd    = $purch_date->format('Y-m-d');  //PurchDate year Month date
        $purchDay = $purch_date->format('d');
        if ($purchDay == 1) {
            $purchDay = 0;
        }
        $owdv_date = $purch_date;
        // if ($pdymd >= ($year - 1) . '-07-01' & $pdymd <= $year . '-06-30') {
        //     $owdv_date = $purch_date;
        // } else {
        //     $owdv_date = makeBackendCompatibleDate('01/07/' . ($year - 1));
        //     $month = 12;
        // }

        if (in_array($purch_date->format('m'), range(1, 6))) {
            $start_year = $purch_date->format('Y') - 1;
            $end_year   = $purch_date->format('Y');
        } else {
            $start_year = $purch_date->format('Y');
            $end_year   = $purch_date->format('Y')+1;
        }

        if($start_year == $year || $end_year == $year){
            switch ($purch_date->format('m')) {
                case '7':
                    $month = 12;
                    $day = 365 - $purchDay;
                    break;
                case '8':
                    $month = 11;
                    $day = 334 - $purchDay;
                    break;
                case '9':
                    $month = 10;
                    $day = 303 - $purchDay;
                    break;
                case '10':
                    $month = 9;
                    $day = 273 - $purchDay;
                    break;
                case '11':
                    $month = 8;
                    $day = 242 - $purchDay;
                    break;
                case '12':
                    $month = 7;
                    $day = 212 - $purchDay;
                    break;
                case '1':
                    $month = 6;
                    $day = 181 - $purchDay;
                    break;
                case '2':
                    $month = 5;
                    $day = 150 - $purchDay;
                    break;
                case '3':
                    $month = 4;
                    $day = 122 - $purchDay;
                    break;
                case '4':
                    $month = 3;
                    $day = 91 - $purchDay;
                    break;
                case '5':
                    $month = 2;
                    $day = 61 - $purchDay;
                    break;
                default:
                    $month = 1;
                    $day = 30 - $purchDay;
                    break;
            }
        }else{
            $month = 12;
            $day = 365;
        }
        
        if ($request->has($request->disposal_date)) {
            $disposal_date = makeBackendCompatibleDate($request->disposal_date);
        } else {
            $disposal_date = null;
        }
        $assetName = DepAssetName::findOrFail($request->dep_subgrup);
        $depGroup  = Depreciation::with('da_code', 'ada_code', 'client', 'profession')->findOrFail($request->dep_grupid);

        $data      = [
            'owdv_value_date' => $owdv_date,
            'year'            => $year,
            'owdv_value'      => $request->actual_owdv,
            'purchase_date'   => $purch_date,
            'purchase_rate'   => $request->purchase_rate,
            'adjust_dep'      => $request->balancingin,
            'purchase_value'  => $request->purchaseprince,
            'disposal_date'   => $disposal_date,
            'disposal_value'  => $request->disposal_price,
            'total_value'     => $request->purchaseprince,
            'dep_method'      => $request->depreciation_method,
            'dep_rate'        => $request->depreciation_rate,
            'dep_amt'         => $request->actual_depreciation,
            'cwdv_value'      => $request->actual_cwdv,
            'profit'          => null,
            'loss'            => null,
        ];
        if ($assetName->year == null) {
            DB::beginTransaction();
            $assetName->update($data);
            $depGroup->update(['year' => $year]);

            // Ledger Table Calculations
            if ($year % 4 == 0) {
                $yearDay = $day + 1;
            } else {
                $yearDay = $day;
            }
            $dep_amt = ($request->actual_depreciation / $yearDay);

            // $ledger['date']                   = $owdv_date;
            $ledger['narration']              = $assetName->asset_name;
            $ledger['source']                 = "DEP";
            $ledger['loan']                   = 
            $ledger['debit']                  = 
            $ledger['credit']                 = 
            $ledger['gst']                    = 
            $ledger['client_account_code_id'] = 
            $ledger['balance']                = 0;
            $ledger['client_id']              = $client_id     = $depGroup->client_id;
            $ledger['profession_id']          = $profession_id = $depGroup->profession_id;

            $tran_array = [];
            for ($t = 1; $t <= $month; $t++) {
                $tran_array[$t] = $trnid = transaction_id('DEP');
                // DB::table('assetname_transaction')->insert([
                //     'dep_asset_name_id' => $assetName->id,
                //     'tran_id'           => $trnid
                // ]);
            }

            //? Depreciation Calculations
            $m = $owdv_date->format('m');
            $M = 1;

            if ($depGroup->dep_acc) {
                for ($i = 1; $i <= $month; $i++) {
                    $mm = $m++;
                    if ($mm >= 7 && $mm <= 12) {
                        $date = makeBackendCompatibleDate('01/' . $mm . '/' . ($year - 1));
                    } else {
                        $mo = $mm>12?$M++:$mm;
                        $date = makeBackendCompatibleDate('01/' . $mo . '/' . $year);
                    }
                    $ledger['date'] = makeBackendCompatibleDate(date('t', strtotime($date)) . $date->format('/m/Y'));
                    // $ledger['transaction_id'] = $ledger['date']->format('dmY').$client_id.$profession_id.$depGroup->id.$assetName->id;
                    $ledger['transaction_id']         = $tran_array[$i];
                    $ledger['chart_id']               = $depGroup->da_code->code;
                    $ledger['client_account_code_id'] = $depGroup->da_code->id;
                    $ledger['credit']                 = 0;

                    if ($purch_date->format('m') == $date->format('m')) {
                        $ledger['debit']   = 
                        $ledger['balance'] = 
                        $dep_amt * (date('t', strtotime($ledger['date'])) - $purchDay);
                    } else {
                        $ledger['debit']   = 
                        $ledger['balance'] = 
                        $dep_amt * date('t', strtotime($ledger['date']));
                    }
                    $ledger['balance_type']           = 1;
                    $dep_acc_ledger = GeneralLedger::create($ledger);
                    DisposeableLedger::create([
                        'dep_asset_name_id' => $assetName->id,
                        'general_ledger_id' => $dep_acc_ledger->id,
                        'batch'             => $assetName->batch,
                    ]);

                    // Retain Earning For each Transaction
                    RetainEarning::tranRetain($client_id, $profession_id, $ledger['transaction_id'], $ledger, ['DEP', 'DEP']);
                }
            }

            //? Asset Name Calculation
            $m = $owdv_date->format('m');
            $M = 1;
            if ($depGroup->accm_dep_acc) {
                for ($i = 1; $i <= $month; $i++) {
                    $mm = $m++;
                    if ($mm >= 7 && $mm <= 12) {
                        $date = makeBackendCompatibleDate('01/' . $mm . '/' . ($year - 1));
                    } else {
                        $mo = $mm>12?$M++:$mm;
                        $date = makeBackendCompatibleDate('01/' . $mo . '/' . $year);
                    }
                    $ledger['date'] = makeBackendCompatibleDate(date('t', strtotime($date)) . $date->format('/m/Y'));
                    // $ledger['transaction_id'] = $ledger['date']->format('dmY').$client_id.$profession_id.$depGroup->id.$assetName->id;
                    $ledger['transaction_id'] = $tran_array[$i];

                    $ledger['chart_id']               = $depGroup->ada_code->code;
                    $ledger['client_account_code_id'] = $depGroup->ada_code->id;
                    $ledger['debit']                  = 0;

                    if ($purch_date->format('m') == $date->format('m')) {
                        $ledger['credit']  =
                            $ledger['balance'] =
                            $dep_amt * (date('t', strtotime($ledger['date'])) - $purchDay);
                    } else {
                        $ledger['credit']  =
                            $ledger['balance'] =
                            $dep_amt * date('t', strtotime($ledger['date']));
                    }

                    $ledger['balance_type']           = 2;
                    $accm_dep_acc_ledger = GeneralLedger::create($ledger);

                    DisposeableLedger::create([
                        'dep_asset_name_id' => $assetName->id,
                        'general_ledger_id' => $accm_dep_acc_ledger->id,
                        'batch'             => $assetName->batch,
                    ]);
                }
            }

            //RetailEarning Calculation
            RetainEarning::retain($client_id, $profession_id, $date, $ledger, ['DEP', 'DEP']);
            try {
                DB::commit();
                return json_encode(['data' => $data, 'status' => '200', 'return' => $request]);
            } catch (\Exception $e) {
                DB::rollBack();
                return json_encode(['status' => '500', 'return' => $request]);
                // return $e->getMessage();
            }
        } else {
            return json_encode(['status' => '500']);
        }
    }
    // Delete All Asset name
    public function assetNameDestroy(Request $request, DepAssetName $asset)
    {
        $request->validate([
            'password' => 'required|string|max:32',
        ]);
        if (Hash::check($request->password, admin()->password)) {
            // if (count($asset->transactions()) < 1) {
            //     Alert::error('Asset Name has no transaction.');
            //     return redirect()->back();
            // }
            if ($asset->rollover_year == null && $asset->disposal_date == null) {
                DB::beginTransaction();
                if ($asset->purchase_date) {
                    $disposeable_ledger_id = DisposeableLedger::where('id', $asset->id)->orWhere('batch', $asset->batch);

                    $disposeable_ledgers = GeneralLedger::whereIn('id', $disposeable_ledger_id->pluck('general_ledger_id'));

                    GeneralLedger::whereIn('transaction_id', $disposeable_ledgers->pluck('transaction_id'))
                        ->where('chart_id', 999998)->delete();
                    $disposeable_ledgers->delete();
                    $disposeable_ledger_id->delete();

                    // Start Retain Earning
                    if (in_array($asset->purchase_date->format('m'), range(1, 6))) {
                        $start_year = $asset->purchase_date->format('Y') - 1 . '-07-01';
                        $end_year   = $asset->purchase_date->format('Y') . '-06-30';
                    } else {
                        $start_year = $asset->purchase_date->format('Y') . '-07-01';
                        $end_year   = $asset->purchase_date->format('Y') + 1 . '-06-30';
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
                    $retain['balance'] = $retainData;

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
                }
                // End Retain Earning
                try {
                    DB::commit();
                    $asset->delete();
                    toast("Asset name deleted successfull!", 'success');
                    return redirect()->back();
                } catch (\Exception $e) {
                    DB::rollBack();
                    return $e->getMessage();
                }
            } else {
                $msg = "You can't delete this asset name";
            }
        }
        $msg = "Password doesn't match";
        Alert::error($msg);
        return redirect()->back();
    }


    public function updateAssetName(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.depreciation.create')) {
            return $error;
        }
        $this->validate($request, [
            'asset_name'     => 'required',
            'financial_year' => 'required',
        ]);
        $assetName = DepAssetName::findOrFail($request->asset_name);
        // Check Period Lock
        if (periodLock($assetName->client_id, $assetName->purchase_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }

        $financial_year = $request->financial_year;

        $purch_date = $assetName->purchase_date;
        $year       = $request->financial_year;
        $purchDay   = $purch_date->format('d');
        if ($purchDay == 1) {
            $purchDay = 0;
        }

        switch ($purch_date->format('m')) {
            case '7':
                $month = 12;
                $day = 365 - $purchDay;
                break;
            case '8':
                $month = 11;
                $day = 334 - $purchDay;
                break;
            case '9':
                $month = 10;
                $day = 303 - $purchDay;
                break;
            case '10':
                $month = 9;
                $day = 273 - $purchDay;
                break;
            case '11':
                $month = 8;
                $day = 242 - $purchDay;
                break;
            case '12':
                $month = 7;
                $day = 212 - $purchDay;
                break;
            case '1':
                $month = 6;
                $day = 181 - $purchDay;
                break;
            case '2':
                $month = 5;
                $day = 150 - $purchDay;
                break;
            case '3':
                $month = 4;
                $day = 122 - $purchDay;
                break;
            case '4':
                $month = 3;
                $day = 91 - $purchDay;
                break;
            case '5':
                $month = 2;
                $day = 61 - $purchDay;
                break;
            default:
                $month = 1;
                $day = 30 - $purchDay;
                break;
        }
        if ($year % 4 == 0) {
            $yearDay = $day + 1;
        } else {
            $yearDay = $day;
        }
        if ($assetName->year == $financial_year) {
            Alert::warning('You can\'t update same Financial Year Data');
        } elseif (($assetName->year + 1) == $financial_year) {
            $adjust_dep = $assetName->dep_amt + $assetName->adjust_dep;
            $total_value = ($assetName->purchase_value - $adjust_dep);
            $data = [
                'depreciation_id' => $assetName->depreciation_id,
                'year'            => $financial_year,
                'client_id'       => $assetName->client_id,
                'profession_id'   => $assetName->profession_id,
                'asset_name'      => $assetName->asset_name,
                'owdv_value_date' => $assetName->owdv_value_date,
                'owdv_value'      => $assetName->cwdv_value,
                'purchase_date'   => $assetName->purchase_date,
                'purchase_rate'   => $assetName->purchase_rate,
                'purchase_value'  => $assetName->purchase_value,
                'dep_method'      => $assetName->dep_method,
                'dep_rate'        => $dep_rate = $assetName->dep_rate,
                'adjust_dep'      => $adjust_dep,
                'total_value'     => $assetName->total_value,
                'dep_amt'         => $dep_amt = $total_value * ($dep_rate / 100),
                'cwdv_value'      => $total_value - $dep_amt
            ];
            try {
                $is_asset = DepAssetName::where('year', $financial_year)
                    ->where('asset_name', $assetName->asset_name)
                    ->first();
                if ($is_asset == null) {
                    $assetName->update(['status' => 0]);
                    DepAssetName::create($data);
                    Alert::success('Success', 'Inserted Successful');
                } else {
                    Alert::warning('You can\'t Perfrom more than one time');
                }
            } catch (\Throwable $th) {
                Alert::error('Error happen!', $th->getMessage());
            }

            // Ledger Calculations
            $dep_amt = $dep_amt / $yearDay;
            $ledger['date']                   = now();
            $ledger['narration']              = "DEP Asset Update";
            $ledger['source']                 = "DEP";
            $ledger['loan']                   =
                $ledger['debit']                  =
                $ledger['credit']                 =
                $ledger['gst']                    =
                $ledger['client_account_code_id'] =
                $ledger['balance']                = 0;
            $ledger['client_id']              = $client_id     = $assetName->client_id;
            $ledger['profession_id']          = $profession_id = $assetName->profession_id;
            //PL Retain Earning Calculations
            $m = $purch_date->format('m');
            $Mm = 1;

            $tran_array = [];
            for ($t = 1; $t <= $month; $t++) {
                $tran_array[$t] = transaction_id('DEP');
            }

            if ($assetName->depreciation->dep_acc) {
                for ($i = 1; $i <= $month; $i++) {
                    $mm = $m++;
                    if ($mm >= 7 & $mm <= 12) {
                        $date = makeBackendCompatibleDate('01/' . $mm . '/' . ($year - 1));
                        $ledger['date'] = makeBackendCompatibleDate(date('t', strtotime($date)) . $date->format('/m/Y'));
                    } else {
                        $date = makeBackendCompatibleDate('01/' . $Mm++ . '/' . $year);
                        $ledger['date'] = makeBackendCompatibleDate(date('t', strtotime($date)) . $date->format('/m/Y'));
                    }
                    // $ledger['transaction_id'] = $ledger['date']->format('dmY').$client_id.$profession_id.$assetName->depreciation->id.$assetName->id;
                    $ledger['transaction_id']         = $tran_array[$i];
                    $ledger['chart_id']               = $assetName->depreciation->dep_acc->code;
                    $ledger['client_account_code_id'] = $assetName->depreciation->da_code->id;
                    $ledger['credit']                 = 0;

                    if ($purch_date->format('m') == $date->format('m')) {
                        $ledger['debit']  =
                            $ledger['balance'] =
                            $dep_amt * (date('t', strtotime($ledger['date'])) - $purchDay);
                    } else {
                        $ledger['debit']  =
                            $ledger['balance'] =
                            $dep_amt * date('t', strtotime($ledger['date']));
                    }

                    $ledger['balance_type']           = 1;
                    GeneralLedger::create($ledger);

                    // Retain Earning For each Transection
                    RetainEarning::tranRetain($client_id, $profession_id, $ledger['transaction_id'], $ledger, ['DEP', 'DEP']);
                }
            }

            $m = $purch_date->format('m');
            $Mm = 1;

            if ($assetName->depreciation->accm_dep_acc) {
                for ($i = 1; $i <= $month; $i++) {
                    $mm = $m++;
                    if ($mm >= 7 & $mm <= 12) {
                        $date = makeBackendCompatibleDate('01/' . $mm . '/' . ($year - 1));
                        $ledger['date'] = makeBackendCompatibleDate(date('t', strtotime($date)) . $date->format('/m/Y'));
                    } else {
                        $date = makeBackendCompatibleDate('01/' . $Mm++ . '/' . $year);
                        $ledger['date'] = makeBackendCompatibleDate(date('t', strtotime($date)) . $date->format('/m/Y'));
                    }
                    // $ledger['transaction_id'] = $ledger['date']->format('dmY').$client_id.$profession_id.$assetName->depreciation->id.$assetName->id;
                    $ledger['transaction_id']         = $tran_array[$i];
                    $ledger['chart_id']               = $assetName->depreciation->accm_dep_acc;
                    $ledger['client_account_code_id'] = $assetName->depreciation->ada_code->id;
                    $ledger['debit']                  = 0;

                    if ($purch_date->format('m') == $date->format('m')) {
                        $ledger['credit']  =
                            $ledger['balance'] =
                            $dep_amt * (date('t', strtotime($ledger['date'])) - $purch_date->format('d'));
                    } else {
                        $ledger['credit']  =
                            $ledger['balance'] =
                            $dep_amt * date('t', strtotime($ledger['date']));
                    }

                    $ledger['balance_type']           = 2;
                    GeneralLedger::create($ledger);
                }
            }

            //RetailEarning Calculation
            RetainEarning::retain($client_id, $profession_id, $date, $ledger, ['DEP', 'DEP']);
        } elseif (($assetName->year + 1) < $financial_year or $assetName->year > $financial_year) {
            Alert::warning('Please Select Correct Financial Year');
        } else {
            Alert::error('You don\'t have access');
        }
        return back();
    }
}
