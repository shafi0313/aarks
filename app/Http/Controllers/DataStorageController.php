<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use App\Models\Data_storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Actions\DataStore\DataStore;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class DataStorageController extends Controller
{
    public function store(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.adt.create')) {
            return $error;
        }

        $request->validate(
            [
                'amount' => 'nullable|numeric',
                'note'   => 'required|string',
                'date'   => 'required',
            ],
            [
                'note.required' => 'Please insert narration'
            ]
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
            return $e->getMessage();
            Alert::error('Server Side Error!');
            DB::rollBack();
        }
        $client     = Client::find($request->clientId);
        $profession = Profession::find($request->professionId);
        activity()
            ->performedOn(new Period())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'ADT Data Stored'])
            ->log('Add/Edit Data > period > ADT > ' . $client->fullname . ' > ' . $profession->name . ' Add ADT ');
        return redirect()->back();
    }


    public function destroy(Data_storage $dataStore)
    {
        if ($error = $this->sendPermissionError('admin.period.delete')) {
            return $error;
        }
        if (periodLock($dataStore->client_id, $dataStore->trn_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        DB::beginTransaction();
        try {
            DataStore::destroy($dataStore);
            activity()
                ->performedOn(new Period())
                ->withProperties(['client' => $dataStore->client->fullname, 'profession' => $dataStore->profession->name, 'report' => 'ADT Data Deleted'])
                ->log('Add/Edit Data > period > ADT > ' . $dataStore->client->fullname . ' > ' . $dataStore->profession->name . ' Deleted ADT ');
            Alert::success('Deleted!', 'Data Deleted Successful!');
            DB::commit();
        } catch (\Exception $e) {
            return $e->getMessage();
            Alert::error('Server Side Error!');
            DB::rollBack();
        }
        return redirect()->back();
    }
}
