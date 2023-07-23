<?php namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Profession;
use App\Models\AccountCode;
use Illuminate\Http\Request;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Models\AccountCodeCategory;
use App\Http\Controllers\Controller;
use App\Models\ProfessionAccountCode;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\EditAccountCodeRequest;
use App\Http\Requests\AccountCodeCreateRequest;
use App\Actions\AccountCodeActions\EditAccountCodeAction;
use App\Actions\AccountCodeActions\AddProfessionAccountCode;

class ProfessionAccountCodeController extends Controller
{
    public function professionForAccountCode()
    {
        if ($error = $this->sendPermissionError('admin.account-code.index')) {
            return $error;
        }
        $professions = Profession::all();
        return view('admin.profession_account_code.search_by_profession', compact('professions'));
    }

    public function index($profession_id)
    {
        if ($error = $this->sendPermissionError('admin.account-code.index')) {
            return $error;
        }
        $selected_profession = Profession::with('industryCategories')->find($profession_id);
        $this->updateMasterSyncAbility($selected_profession);
        $industry_categories = $selected_profession->industryCategories->pluck('id')->toArray();
        $selected_profession->load([
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
                ])->where('type', 1)->whereNull('parent_id')->orderBy('code', 'asc');
            }
        ]);
        $accountCodes = ProfessionAccountCode::where('profession_id', $profession_id)
            ->with('profession')
            ->orderBy('code', 'asc')->get();
        $industryCategories = $selected_profession->industryCategories;
        $professions = Profession::all();
        $accountCodeCategories = $selected_profession->accountCodeCategories;


        return view(
            'admin.profession_account_code.index',
            compact([
                'professions',
                'industryCategories',
                'accountCodeCategories',
                'selected_profession',
                'accountCodes'
            ])
        );
    }

    private function updateMasterSyncAbility(Profession $profession)
    {
        if ($profession->accountCodes->count() && $profession->can_perform_sync) {
            $profession->can_perform_sync = false;
            $profession->save();
        }
    }

    public function store(AccountCodeCreateRequest $request, AddProfessionAccountCode $addAccountCode)
    {
        if ($error = $this->sendPermissionError('admin.account-code.create')) {
            return $error;
        }
        $data = $this->prepareDataForAccountCode($request);

        DB::beginTransaction();
        try {
            $professionCode = $addAccountCode->setData($data)->execute();
            $clients = Client::leftJoin('client_payment_lists', 'clients.id', '=', 'client_payment_lists.client_id')
            ->select('clients.id','clients.company', 'clients.first_name','clients.last_name','clients.email','clients.phone',
                    'client_payment_lists.status', 'client_payment_lists.is_expire', 'client_payment_lists.status')
            ->orderBy('client_payment_lists.status', 'desc')
            ->orderBy('client_payment_lists.is_expire', 'desc')
            ->get();
            foreach ($clients as $client) {
                $data = [
                    'client_id'              => $client->id,
                    "profession_id"          => $professionCode->profession_id,
                    "category_id"            => $professionCode->category_id,
                    "sub_category_id"        => $professionCode->sub_category_id,
                    "additional_category_id" => $professionCode->additional_category_id,
                    "code"                   => $professionCode->code,
                    "type"                   => $professionCode->type,
                    "name"                   => $professionCode->name,
                    "gst_code"               => $professionCode->gst_code,
                    "note"                   => $professionCode->note,
                ];
                ClientAccountCode::create($data);
            }
            Alert::success('Account Code Create', 'Account Code Successfully Created');
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::error('Account Code Create', $exception->getMessage());
        }
        return redirect()->route('account-code.index', $request->profession_id);
    }

//    public function checkAccountCode(Request $request){
//
//        $accountCode = AccountCode::where('code',(int)$request->account_code)->get();
//        return $accountCode;
//
//    }

    public function prepareDataForAccountCode($request)
    {
        return [
            'profession_id'          => $request->profession_id,
            'industry_category_id'   => $request->industry_category_id,
            'category_id'            => $request->category,
            'sub_category_id'        => $request->sub_category,
            'additional_category_id' => $request->additional_category,
            'code'                   => $request->account_code,
            'type'                   => $request->type,
            'name'                   => $request->account_name,
            'gst_code'               => $request->gst_code,
            'note'                   => $request->note,
        ];
    }

    public function showForm()
    {
        if ($error = $this->sendPermissionError('admin.account-code.index')) {
            return $error;
        }
        $professions = Profession::orderBy('name')->get(['id','name']);
        return view('admin.profession_account_code.search_by_profession', compact('professions'));
    }

    public function editAccountCode(EditAccountCodeRequest $request, EditAccountCodeAction $editAccountCodeAction)
    {
        if ($error = $this->sendPermissionError('admin.account-code.edit')) {
            return $error;
        }
        $request->validated();
        DB::beginTransaction();

        try {
            $type = $request->ac_type;
            if ($type == 'main') {
                AccountCodeCategory::findOrFail($request->id)->update([
                    'name' => $request->name,
                    'note' => $request->note
                ]);
                Alert::success('Category Update', 'Category Successfully Updated');
            } elseif ($type == 'sub') {
                AccountCodeCategory::whereParentId($request->parent_id)->findOrFail($request->id)->update([
                    'name' => $request->name,
                    'note' => $request->note
                ]);
                Alert::success('Sub Category Update', 'Sub Category Successfully Updated');
            } elseif ($type == 'subsub') {
                AccountCodeCategory::whereParentId($request->parent_id)->findOrFail($request->id)->update([
                    'name' => $request->name,
                    'note' => $request->note
                ]);
                Alert::success('Additional Category Update', 'Additional Category Successfully Updated');
            } else {
                $accountCode = ProfessionAccountCode::find($request->id);
                $data = $this->prepareDataForEditAccountCode($request);
                $editAccountCodeAction->setInstance($accountCode)
                    ->setData($data)
                    ->execute();
                Alert::success('Account Code Update', 'Account Code Successfully Updated');
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::error('Account Code Update', $exception->getMessage());
        }

        return redirect()->route('account-code.index', $request->profession_id);
    }
//    public function delete($profession_id, ProfessionAccountCode $account_code)
//    {
//        if ($error = $this->sendPermissionError('admin.account-code.delete')) {
//            return $error;
//        }
//
//        DB::beginTransaction();
//
//        try {
//            $account_code->industryCategories()->detach();
//            $account_code->delete();
//
//            Alert::success('Account Code Delete', 'Account Code Successfully deleted');
//            DB::commit();
//        } catch (\Exception $exception) {
//            DB::rollBack();
//            Alert::error('Account Code Update', $exception->getMessage());
//        }
//        return redirect()->route('account-code.index',$profession_id);
//    }

    public function prepareDataForEditAccountCode($request)
    {
        return[
            'name' => $request->name,
            'note' => $request->note,
        ];
    }
}
