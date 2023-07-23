<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Period;
use App\Models\PeriodLock;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Models\BankStatementImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\UploadBSRequest;
use RealRashid\SweetAlert\Facades\Alert;
use App\Aarks\BankStatementImportCollection;
use App\Actions\BankStatementActions\ImportBS;
use App\Http\Requests\BankStatementPostRequest;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.bs_import.index')) {
            return $error;
        }
        $clients = Client::all(clientSetVisible());
        return view('admin.bs_import.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($error = $this->sendPermissionError('admin.bs_import.create')) {
            return $error;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UploadBSRequest $request
     * @param BankStatementImportCollection $bankStatementImport
     * @return \Illuminate\Http\Response
     */
    public function store(UploadBSRequest $request, BankStatementImportCollection $bankStatementImport)
    {
        if ($error = $this->sendPermissionError('admin.bs_import.create')) {
            return $error;
        }
        try {
            Excel::import($bankStatementImport, $request->file);
            $bankStatementImport->tempSolution($request->client_id, $request->profession_id);            
            // Alert::success('Upload Bank Statement', 'Bank statement was successfully uploaded');
        } catch (\Exception  $exception) {
            Alert::error('Upload Bank Statement', $exception->getMessage());
        }

        return redirect()->route('bs_import.BS', ['client' => $request->client_id, 'profession' => $request->profession_id]);
    }

    public function updateAccountCode(Request $request, $id)
    {
        try {
            $import = BankStatementImport::find($id);
            if (periodLock($import->client_id, ($import->date))) {
                return response()->json(['status'=>500,'message'=> 'Your enter data period is locked, check administration']);
            }
            $import->update(['account_code' => $request->accountCode]);
            return response()->json(['status'=>200,'message'=> 'Account code successfully updated.']);
        } catch (\Exception $e) {
            return response()->json(['status'=>500,'message'=> $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Import  $import
     * @return \Illuminate\Http\Response
     */
    public function edit(BankStatementImport $import)
    {
        if ($error = $this->sendPermissionError('admin.bs_import.edit')) {
            return $error;
        }
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Import  $import
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankStatementImport $import)
    {
        if ($error = $this->sendPermissionError('admin.bs_import.edit')) {
            return $error;
        }
    }

    public function showProfessions(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.bs_import.index')) {
            return $error;
        }
        return view('admin.bs_import.select_profession', compact('client'));
    }


    public function showBS(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.bs_import.index')) {
            return $error;
        }
        // $dates = ['2016-06-30', '2016-07-30' , '2016-08-30'];
        // return PeriodLock::where('client_id', $client->id)
        // ->whereIn('date', $dates)
        // ->first();
        $bank_statements = BankStatementImport::where('is_posted', 0)->with('client_account_code')
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('date', 'asc')
            ->paginate(50);
        $period = Period::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->first();

        $account_codes = ClientAccountCode::where('client_id', $client->id)
                        ->where('profession_id', $profession->id)
                        ->where('code', 'not like', '99999%')
                        ->orderBy('code', 'asc')->get();
        $liquid_asset_account_codes = ClientAccountCode::where('additional_category_id', aarks('liquid_asset_id'))
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('code', 'asc')
            ->get();
        if ($period) {
            return view('admin.bs_import.import', compact('period', 'client', 'profession', 'bank_statements', 'account_codes', 'liquid_asset_account_codes'));
        } else {
            toast('You must create a period before import!', 'warning');
            return back();
        }
    }


    public function post(Request $request, ImportBS $importBs)
    {
        if ($error = $this->sendPermissionError('admin.bs_import.create')) {
            return $error;
        }
        $this->validate($request, [
            'bank_account'  => 'required',
            'client_id'     => 'required',
            'profession_id' => 'required'
        ]);
        $importBs->post($request);
        return back();
    }


    // public function post(BankStatementPostRequest $request,ImportBankStatement $importBankStatement)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $importBankStatement->setClient(Client::find($request->client_id))
    //             ->setProfession(Profession::find($request->profession_id))
    //             ->setBankAccount(ClientAccountCode::find($request->bank_account))
    //             ->execute();
    //         Alert::success('Success','Action Successful');
    //         DB::commit();
    //     }catch (\Exception $exception){
    //         dd($exception);
    //         DB::rollBack();
    //         Alert::error('Error',$exception->getMessage());
    //     }
    //     return back();
    // }

    public function deleteAll(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.bs_import.edit')) {
            return $error;
        }
        DB::beginTransaction();
        try {
            $dates = BankStatementImport::where('is_posted', 0)
                ->where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)
                ->pluck('date')->toArray();
            if (periodLock($request->client_id, $dates)) {
                Alert::error('Your enter data period is locked, check administration');
                return back();
            }
            BankStatementImport::where('is_posted', 0)
                ->where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)
                ->delete();
            Alert::success('Success', 'Action Successful');
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::error('Error', $exception->getMessage());
        }
        return back();
    }
}
