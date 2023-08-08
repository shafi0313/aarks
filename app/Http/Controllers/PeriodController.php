<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Payable;
use App\Models\FuelTaxLtr;
use App\Models\Profession;
use App\Models\Data_storage;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\Fuel_tax_credit;
use App\Models\ClientAccountCode;
use App\Models\BankReconciliation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\CreateClientPeriodRequest;
use App\Actions\ClientPeriod\CreateClientPeriodAction;
use App\Models\BankReconciliationAdmin;
use App\Models\BankReconciliationLedger;
use App\Models\BankStatementImport;
use App\Models\BankStatementInput;

class PeriodController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPassword')->only(['destroy']);
    }

    public function selectMethod()
    {
        return view('admin.period.select_method');
    }

    public function index()
    {
        if ($error = $this->sendPermissionError('admin.period.index')) {
            return $error;
        }
        return view('admin.period.search_financial_period_by_client');
    }

    public function store(CreateClientPeriodRequest $request, CreateClientPeriodAction $createClientPeriodAction)
    {
        if ($error = $this->sendPermissionError('admin.period.create')) {
            return $error;
        }
        $client     = Client::find($request->client_id);
        $profession = Profession::find($request->profession_id);
        $data       = [
            'year'          => $request->year,
            'start_date'    => makeBackendCompatibleDate($request->start_date),
            'end_date'      => makeBackendCompatibleDate($request->end_date),
            'client_id'     => $client->id,
            'profession_id' => $profession->id
        ];

        // Check Period Lock
        if (periodLock($request->client_id, $data['end_date'])) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }

        try {
            $createClientPeriodAction->setData($data)->execute();
            Alert::success('Client Period', 'Client Period Recorded Successfully');
        } catch (\Exception $exception) {
            Alert::error('Client Period', $exception->getMessage());
        }
        activity()
            ->performedOn(new Period())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'New Period created'])
            ->log('Add/Edit Data > Period > ' . $client->fullname . ' > ' . $profession->name . ' Add Period');

        return redirect()->back();
    }

    public function show($client_id)
    {
        if ($error = $this->sendPermissionError('admin.period.index')) {
            return $error;
        }
        $profession = Profession::with('clients')->find($client_id);

        foreach ($profession->clients as $client) {
            $c_id = $client->id;
        }

        $client = Client::with('periods')->find($c_id);
        return view('admin.period.index', compact(['client', 'profession']));
    }


    public function getClient(Request $request)
    {
        $clients = Client::where('first_name', 'like', '%' . $request->q . '%')->orWhere('company', 'like', '%' . $request->q . '%')
            ->select('id', 'first_name', 'last_name', 'company')
            ->get()
            ->map(function ($client) {
                return [
                    'id' => route('client-pro', $client->id),
                    "text" => $client->company . '-' . $client->full_name
                ];
            })->toArray();

        return $clients;
    }


    public function client_pro($client_id)
    {
        if ($error = $this->sendPermissionError('admin.adt.index')) {
            return $error;
        }
        $client = Client::with('professions')->find($client_id);
        return view('admin.period.period_profession', compact('client'));
    }

    public function sh($client_id, $pro_id)
    {
        if ($error = $this->sendPermissionError('admin.adt.index')) {
            return $error;
        }
        $client     = Client::find($client_id);
        $profession = Profession::find($pro_id);
        $periods    = Period::where('client_id', $client_id)
            ->where('profession_id', $pro_id)
            ->orderBy('end_date', 'desc')
            ->get();
        return view('admin.period.index', compact(['client', 'profession', 'periods']));
    }

    public function editperiod($profession_id, $period_id, $client_id)
    {
        if ($error = $this->sendPermissionError('admin.adt.edit')) {
            return $error;
        }

        $profession = Profession::find($profession_id);
        $period      = Period::find($period_id);
        $client      = Client::find($client_id);
        $account_codes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession_id)
            ->where('code', 'not like', '912%')
            ->where('code', 'not like', '954%')
            ->where('code', 'not like', '999%')
            ->orderBy('code')->get();
        $fuel    = Fuel_tax_credit::where('start_date', '<=', $period->end_date)->where('end_date', '>=', $period->end_date)->first();
        $fuelLtr = FuelTaxLtr::where('client_id', $client_id)->where('profession_id', $profession_id)->where('period_id', $period_id)->get();
        $payable = Payable::where('client_id', $client_id)->where('profession_id', $profession_id)->first();

        // Check Period Lock
        if (periodLock($client->id, $period->end_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }

        return view('admin.period.edit', compact(['payable', 'profession', 'period', 'client', 'fuel', 'fuelLtr', 'account_codes']));
    }

    public function sub_pro_show($sub_id, $sub_code, $period_id, $pro_id, $client_id)
    {
        if ($error = $this->sendPermissionError('admin.adt.edit')) {
            return $error;
        }
        $period         = Period::find($period_id);
        $profession     = Profession::find($pro_id);
        $client         = Client::find($client_id);
        $client_account = ClientAccountCode::findOrFail($sub_id);
        $account_codes  = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $pro_id)
            ->where('code', 'not like', '912%')
            ->where('code', 'not like', '954%')
            ->where('code', 'not like', '999%')
            ->orderBy('code')->get();
        $fuel    = Fuel_tax_credit::where('start_date', '<=', $period->end_date)
            ->where('end_date', '>=', $period->end_date)->first();
        $fuelLtr = FuelTaxLtr::where('client_id', $client_id)
            ->where('profession_id', $pro_id)
            ->where('period_id', $period_id)->get();
        $data    = Data_storage::where('chart_id', $sub_code)
            ->where('period_id', $period_id)->get();
        $payable = Payable::where('client_id', $client_id)
            ->where('profession_id', $pro_id)->first();
        return view('admin.period.sub_pro_edit', compact(['payable', 'period', 'profession', 'client', 'data', 'client_account', 'fuel', 'fuelLtr', 'account_codes']));
    }

    public function destroy(Period $period)
    {
        if ($error = $this->sendPermissionError('admin.period.index')) {
            return $error;
        }
    
        $start_date = $period->start_date->format('Y-m-d');
        $end_date = $period->end_date->format('Y-m-d');
        $profession_id = $period->profession_id;
        $client_id = $period->client_id;

        Data_storage::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->where('period_id', $period->id)
            ->delete();

        BankReconciliation::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('date', [$start_date, $end_date])
            ->delete();

        BankReconciliationAdmin::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('date', [$start_date, $end_date])
            ->delete();

        BankReconciliationLedger::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('date', [$start_date, $end_date])
            ->delete();

        BankStatementImport::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('date', [$start_date, $end_date])
            ->delete();

        BankStatementInput::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('date', [$start_date, $end_date])
            ->delete();

        Gsttbl::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->where('period_id', $period->id)
            ->delete();

        GeneralLedger::where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('date', [$start_date, $end_date])
            ->delete();


        $client_id = $period->client_id; // replace with the actual client ID
        return$tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        $affected_rows = 0;

        DB::beginTransaction();

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'client_id')) {
                $affected_rows += DB::table($table)->where('client_id', $client_id)->delete();
            }
        }










        // $gst = Gsttbl::where('period_id', $period->id)->where('client_id', $period->client_id)->first();
        // if ($gst) {
        //     Alert::error('Client Period', 'You can not delete this period because GST is already added');
        //     return back();
        // }

        // // Check Period Lock
        // if (periodLock($period->client_id, $period->end_date)) {
        //     Alert::error('Your enter data period is locked, check administration');
        //     return back();
        // }
        // try {
        //     $period->delete();
        //     Alert::success('Deleted', 'Period Deleted Successfully');
        // } catch (\Exception $exception) {
        //     Alert::error('Deleted', $exception->getMessage());
        // }
        activity()
            ->performedOn(new Period())
            ->withProperties(['client' => $period->client->fullname, 'profession' => $period->profession->name, 'report' => 'Period Deleted'])
            ->log('Add/Edit Data > Period > ' . $period->client->fullname . ' > ' . $period->profession->name . ' Delete Period');
        return redirect()->back();
    }
}
