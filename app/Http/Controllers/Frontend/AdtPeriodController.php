<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Client;
use App\Models\Period;
use App\Models\Payable;
use App\Models\FuelTaxLtr;
use App\Models\Profession;
use App\Models\Data_storage;
use Illuminate\Http\Request;
use App\Models\Fuel_tax_credit;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Actions\DataStore\DataStore;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Actions\ClientPeriod\CreateClientPeriodAction;

class AdtPeriodController extends Controller
{
    public function index()
    {
        $client = Client::findOrFail(client()->id);
        return view('frontend.accounts.period.index', compact('client'));
    }
    public function periodIndex(Client $client, Profession $profession)
    {
        $periods = Period::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('end_date', 'desc')
            ->get();

        return view('frontend.accounts.period.period', compact(['client', 'periods','profession']));
    }
    public function periodStore(Request $request, CreateClientPeriodAction $createClientPeriodAction)
    {
        $request->validate([
            'year'       => 'required',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date'   => 'required|date_format:d/m/Y',
            'client_id'  => 'required'
        ]);
        $data =[
            'year'          => $request->year,
            'start_date'    => makeBackendCompatibleDate($request->start_date),
            'end_date'      => makeBackendCompatibleDate($request->end_date),
            'client_id'     => $request->client_id,
            'profession_id' => $request->profession_id,
            'can_delete'    => 1,
        ];

        try {
            $createClientPeriodAction->setData($data)->execute();
            toast('Client Period Recorded Successfully', 'success');
        } catch (\Exception $exception) {
            toast($exception->getMessage(), 'error');
        }
        return redirect()->back();
    }
    public function periodDelete(Period $period)
    {
        try {
            $period->delete();
            Alert::success('Client Period', 'Period Deleted Successfully');
        } catch (\Exception $exception) {
            Alert::error('Client Period', $exception->getMessage());
        }
        return redirect()->back();
    }
    public function periodAddEdit(Client $client, Profession $profession, Period $period)
    {
        $profession->load(['accountCodes']);
        $client->load(['professions','account_codes'=>function ($q) use ($profession) {
            $q->where('profession_id', $profession->id)->orderBy('code');
        }]);
        $payable =  Payable::where('client_id', $client->id)->where('profession_id', $profession->id)->first();
        $fuel = Fuel_tax_credit::where('start_date', '<=', $period->end_date)->where('end_date', '>=', $period->end_date)->first();
        $fuelLtr = FuelTaxLtr::where('client_id', $client->id)->where('period_id', $period->id)->get();

        // return $client;
        return view('frontend.accounts.period.edit', compact(['payable','profession', 'period', 'client','fuelLtr','fuel']));
    }
    public function periodCodeAddEdit(ClientAccountCode $client_account, $code, Client $client, Profession $profession, Period $period)
    {
        $profession->load(['accountCodes']);
        $client->load(['professions','account_codes'=>function ($q) use ($profession) {
            $q->where('profession_id', $profession->id)->orderBy('code');
        }]);
        $payable =  Payable::where('client_id', $client->id)->where('profession_id', $profession->id)->first();

        $data = Data_storage::where('chart_id', $code)->where('period_id', $period->id)->get();
        $balance = Data_storage::where('chart_id', $code)->where('period_id', $period->id)->sum('balance');

        $fuel = Fuel_tax_credit::where('start_date', '<=', $period->end_date)->where('end_date', '>=', $period->end_date)->first();
        $fuelLtr = FuelTaxLtr::where('client_id', $client->id)->where('period_id', $period->id)->get();

        // return $client;
        return view('frontend.accounts.period.sub_edit', compact(['payable','profession', 'period', 'client','data','balance','client_account','fuelLtr','fuel']));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
            'amount' => 'nullable|numeric',
            'note'   => 'required|string',
            'date'   => 'required'
        ],
            ['note.required' => 'Please insert narration']
        );

        if (periodLock($request->clientId, makeBackendCompatibleDate($request->date))) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }

        DB::beginTransaction();
        try {
            DataStore::store($request);
            DB::commit();
            Alert::success('Inserted!', 'Data Stored Successful!');
        } catch (\Exception $e) {
            // return $e->getMessage();
            Alert::error('Server Side Error!');
            DB::rollBack();
        }

        return redirect()->back();
    }


    public function destroy(Data_storage $adtperiod)
    {
        if (periodLock($adtperiod->client_id, $adtperiod->trn_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        DB::beginTransaction();
        try {
            DataStore::destroy($adtperiod);
            DB::commit();
            Alert::success('Deleted!', 'Data Delete Successful!');
        } catch (\Exception $e) {
            // return $e->getMessage();
            Alert::error('Server Side Error!');
            DB::rollBack();
        }
        return redirect()->back();
    }
}
