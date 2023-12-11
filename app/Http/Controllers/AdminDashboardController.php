<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use App\Models\BudgetEntry;
use App\Models\VisitorInfo;
use App\Models\DepAssetName;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\MasterAccountCode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use RealRashid\SweetAlert\Facades\Alert;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $clients = Client::pluck('id')->toArray();
        foreach ($clients as $client) {
            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            foreach ($tables as $table) {
                if (Schema::hasColumn($table, 'client_id') && Schema::hasColumn($table, 'deleted_at')) {
                    DB::table($table)->where('client_id', $client)->whereDate('deleted_at', '2023-12-11')->update(['deleted_at' => null]);
                }
            }

            Client::where('id', $client)->whereDate('deleted_at', '2023-12-11')->update(['deleted_at' => null]);
        }
        // do {
        //     DB::table('trxid')->insert(['trxid' => transaction_id('TRX')]);
        // } while (true);
        // $admin = auth('admin')->user();
        // $admin->assignRole('Admin');
        // return$admin->getRoleNames();

        // return DepAssetName::find(66)->transactions();
        // exec("free -mtl", $output);
        // return $output;
        $clientCount = Client::count();
        $profession  = Profession::count();
        $adminUser   = Admin::count();
        $acCode      = MasterAccountCode::count();
        $visitors    = VisitorInfo::count();

        //Get Total Memory
        // $data = explode("\n", file_get_contents("/proc/meminfo"));
        // $meminfo = [];
        // foreach ($data as $line) {
        //     @list($key, $val) = explode(":", $line);
        //     $meminfo[$key] = trim($val);
        // }
        // end
        return view('admin.dashboard', compact(['clientCount', 'profession', 'adminUser', 'acCode', 'visitors']));
    }
    public function profile(Request $request)
    {
        $user = admin();
        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        // Add the secret key to the registration data
        $secret_key = $google2fa->generateSecretKey();

        // Generate the QR image. This is the image the user will scan with their app
        // to set up two factor authentication
        $QR = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret_key
        );
        return view('admin.profile.index', compact(['user', 'QR', 'secret_key']));
    }
    public function selectTwo(Request $request)
    {
        if ($request->ajax()) {
            switch ($request->type) {
                case 'getClient':
                    $response = Client::where('first_name', 'like', '%' . $request->q . '%')->orWhere('company', 'like', '%' . $request->q . '%')
                        ->select('id', 'first_name', 'last_name', 'company')
                        ->get()
                        ->map(function ($client) {
                            return [
                                'id' => $client->id,
                                // 'id' => route('client-pro', $client->id),
                                "text" => $client->company . '-' . $client->full_name
                            ];
                        })->toArray();
                    break;
                case 'getProfession':
                    $response = Client::findOrFail($request->client_id)->professions()->where('name', 'like', '%' . $request->q . '%')
                        ->where('client_id', $request->client_id)
                        ->select('professions.id', 'professions.name')
                        ->get()
                        ->map(function ($profession) {
                            return [
                                'id'   => $profession->id,
                                "text" => $profession->name
                            ];
                        })->toArray();
                    break;
                case 'getDates':
                    $years = BudgetEntry::where('client_id', $request->client_id)->where('profession_id', $request->profession_id)->selectRaw('YEAR(date) as year,date')->get()->groupBy(['year', 'date']);
                    $html = '';
                    foreach ($years as $i => $year) {
                        $ii = $i + 1;
                        $html .= "<tr><td>{$ii} =>";
                        foreach ($year as $date) {
                            $date1 = $date->first()->date->format('d/m/Y');
                            $date2 = $date->first()->date->addYear()->format('d/m/Y');
                            $html .= "<div class='checkbox-inline d-inline px-4'>
                        <label><input type='radio' name='date' value='{$date1}' required> &nbsp; {$date2}</label>
                    </div>";
                        }
                        "</td></tr>";
                    }
                    $response = response()->json(['tr' => $html]);
                    break;
                case 'getFinancialDates':
                    $years = BudgetEntry::where('client_id', $request->client_id)->where('profession_id', $request->profession_id)->selectRaw('id,year,start_date,end_date')->get()->groupBy(['year']);
                    $html = '';
                    foreach ($years as $i => $year) {
                        $html .= "<tr><td>{$i} =>";
                        foreach ($year as $dates) {
                            $id = $dates->first()->id;
                            $start_date = $dates->first()->start_date->format('d/m/Y');
                            $end_date = $dates->first()->end_date->format('d/m/Y');
                            $html .= "<div class='checkbox-inline d-inline px-4'>
                        <label><input type='checkbox' name='period_ids[]' value='{$id}'> &nbsp; {$start_date} to {$end_date}</label>
                    </div>";
                        }
                        "</td></tr>";
                    }
                    $response = response()->json(['tr' => $html]);
                    break;
                case 'getFinancialYear':
                    $years = Period::where('client_id', $request->client_id)->where('profession_id', $request->profession_id)->selectRaw('year')->get()->groupBy(['year']);
                    $html = '';
                    foreach ($years as $i => $year) {
                        $html .= "<tr><td><div class='checkbox-inline d-inline px-4'>
                        <label><input type='radio' name='year' value='{$i}'> &nbsp; {$i}</label>
                    </div></td></tr>";
                    }
                    $response = response()->json(['tr' => $html]);
                    break;
                default:
                    $response = [];
                    break;
            }
            return $response;
        }
        abort(404);
    }
}
