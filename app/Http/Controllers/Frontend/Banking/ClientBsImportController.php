<?php

namespace App\Http\Controllers\Frontend\Banking;

use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Models\BankStatementImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use App\Aarks\BankStatementImportCollection;
use App\Actions\BankStatementActions\ImportBS;
use App\Http\Requests\BankStatementPostRequest;

class ClientBsImportController extends Controller
{
    public function index()
    {
        $client = Client::findOrFail(client()->id);
        return view('frontend.banking.import.index', compact('client'));
    }

    public function show(Client $client, Profession $profession)
    {
        $bank_statements = BankStatementImport::where('is_posted', 0)->with('client_account_code')
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('date', 'asc')
            ->paginate(100);
        $period = Period::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->first();

        $account_codes              = ClientAccountCode::where('client_id', $client->id)->where('profession_id', $profession->id)->orderBy('code', 'asc')->get();
        $liquid_asset_account_codes = ClientAccountCode::where('additional_category_id', aarks('liquid_asset_id'))
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('code', 'asc')
            ->get();
        if ($period) {
            return view('frontend.banking.import.import', compact('period', 'client', 'profession', 'bank_statements', 'account_codes', 'liquid_asset_account_codes'));
        } else {
            toast('You must create a period before import!', 'warning');
            return back();
        }
    }

    public function store(Request $request, BankStatementImportCollection $bankStatementImport)
    {
        try {
            Excel::import($bankStatementImport, $request->file);
            $bankStatementImport->tempSolution($request->client_id, $request->profession_id);
            toast('Bank statement was successfully uploaded', 'success');
        } catch (\Exception  $exception) {
            toast($exception->getMessage(), 'error');
        }
        return redirect()->route('cbs_import.show', ['client' => $request->client_id, 'profession' => $request->profession_id]);
    }

    public function updateAccountCode(Request $request, $id)
    {
        try {
            $import = BankStatementImport::where('id', $id)->first();
            if (periodLock($import->client_id, $import->date)) {
                return response()->json(['message'=>'Your enter data period is locked, check administration'], 500);
            }
            $import->update(['account_code' => $request->accountCode]);
            return response()->json(['status'=>200,'message'=> 'Account code successfully updated.']);
            // return $import;
        } catch (\Exception $exception) {
            return 0;
        }
    }

    public function post(Request $request, ImportBS $importBs)
    {
        $this->validate($request, [
            'bank_account'  => 'required',
            'client_id'     => 'required',
            'profession_id' => 'required'
        ]);
        $importBs->post($request);
        return back();
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $date = BankStatementImport::where('is_posted', 0)
                ->where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)
                ->first()->date;
            if (periodLock($request->client_id, $date)) {
                Alert::error('Your enter data period is locked, check administration');
                return back();
            }
            BankStatementImport::where('is_posted', 0)
                ->where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)
                ->delete();
            Alert::success('Success', 'Delete Successful');
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::error('Error', 'Server Side Error');
            // Alert::error('Error', $exception->getMessage());
        }
        return back();
    }
}
