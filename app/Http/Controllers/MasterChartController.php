<?php namespace App\Http\Controllers;

use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\IndustryCategory;
use App\Models\MasterAccountCode;
use Illuminate\Support\Facades\DB;
use App\Models\AccountCodeCategory;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\EditAccountCodeRequest;
use App\Http\Requests\SubCategoryCreateRequest;
use App\Actions\MasterChartActions\SyncMasterChart;
use App\Http\Requests\AdditionalCategoryCreateRequest;
use App\Actions\AccountCodeActions\EditAccountCodeAction;
use App\Http\Requests\MasterChartAccountCodeCreateRequest;
use App\Actions\AccountCodeActions\AddAccountCodeWithoutProfession;
use App\Actions\AccountCodeActions\AddAdditionalCategoryWithoutProfession;

class MasterChartController extends Controller
{
    public function showMasterChart()
    {
        if ($error = $this->sendPermissionError('admin.master-chart.index')) {
            return $error;
        }
        $industryCategories = IndustryCategory::pluck('name', 'id');
        $accountCodeCategories = AccountCodeCategory::with('subCategory', 'industryCategories')->where('type', 1)->get();
        $masterAccountCodes = MasterAccountCode::orderBy('code')->get();

        return view('admin.master_chart.index', compact('accountCodeCategories', 'masterAccountCodes', 'industryCategories'));
    }
    public function sync(Profession $profession, SyncMasterChart $syncMasterChart)
    {
        DB::beginTransaction();
        try {
            $syncMasterChart->setProfession($profession)->execute();
            DB::commit();
            Alert::success('Master Code Sync', 'Master Code Sync Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::error('Master Code Sync', $exception->getMessage());
        }

        return redirect()->back();
    }

    public function addSubCategory(SubCategoryCreateRequest $request, AddAdditionalCategoryWithoutProfession $addAdditionalCategoryWithoutProfession)
    {
        if ($error = $this->sendPermissionError('admin.master-chart.sub-category.create')) {
            return $error;
        }

        $data = $this->prepareDataForSubCategory($request);

        DB::beginTransaction();

        try {
            $addAdditionalCategoryWithoutProfession->setData($data)->execute();
            Alert::success('Sub Group', 'Sub Group Created Successfully')->autoClose(3000);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::error('Sub Group', $exception->getMessage())->autoClose(3000);
        }

        return redirect()->route('master.chart');
    }
    public function addAdditionalCategory(AdditionalCategoryCreateRequest $request, AddAdditionalCategoryWithoutProfession $addAdditionalCategoryWithoutProfession)
    {
        if ($error = $this->sendPermissionError('admin.master-chart.additional-category.create')) {
            return $error;
        }
        $data = $this->prepareDataForAdditionalCategory($request);
        DB::beginTransaction();

        try {
            $addAdditionalCategoryWithoutProfession->setData($data)->execute();
            Alert::success('Sub Sub Group', 'Sub Sub Group Created Successfully');
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::error('Sub Group', $exception->getMessage());
        }

        return redirect()->route('master.chart');
    }
    public function addAccountCode(AddAccountCodeWithoutProfession $addMasterChart, MasterChartAccountCodeCreateRequest $request)
    {
        if ($error = $this->sendPermissionError('admin.master-chart.create')) {
            return $error;
        }

        $data = $this->prepareDataForAccountCode($request);
        DB::beginTransaction();

        try {
            $addMasterChart->setData($data)->execute();
            Alert::success('Account Code', 'Account Code Created Successfully');
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
            Alert::error('Account Code', $exception->getMessage());
        }

        return redirect()->route('master.chart');
    }

    public function editAccountCode(EditAccountCodeRequest $request, EditAccountCodeAction $editAccountCodeAction)
    {
        if ($error = $this->sendPermissionError('admin.master-chart.edit')) {
            return $error;
        }

        DB::beginTransaction();

        try {
            $type = $request->ac_type;
            if ($type == 'category') {
                AccountCodeCategory::whereParentId($request->parent_id)->findOrFail($request->id)->update([
                    'name' => $request->name,
                    'note' => $request->note
                ]);
                Alert::success('Category Update', 'Category Successfully Updated');
            } else {
                $accountCode = MasterAccountCode::find($request->id);
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

        return redirect()->route('master.chart');
    }
    public function deleteAdditionalCategory(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.master-chart.delete')) {
            return $error;
        }
        $additionalCategory = AccountCodeCategory::find($request->id);

        try {
            if ($additionalCategory->is_deletable) {
                $account_codes = MasterAccountCode::where('additional_category_id', $additionalCategory->id)->get();

                foreach ($account_codes as $account_code) {
                    $account_code->industryCategories()->detach();
                    $account_code->delete();
                }

                $additionalCategory->professions()->detach();
                $additionalCategory->industryCategories()->detach();
                $additionalCategory->delete();
//
//            //Sync will be hed here
//
                Alert::success('Sub Sub Group Delete', 'Sub Sub Group Successfully deleted');
            } else {
                Alert::success('Sub Sub Group Delete', 'Sub Sub Group Not deletable');
            }
        } catch (\Exception $exception) {
            Alert::error('Sub Sub Group Delete', $exception->getMessage());
        }
        return redirect()->route('master.chart');
    }

    public function delete(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.master-chart.delete')) {
            return $error;
        }

        $account_code = MasterAccountCode::find($request->id);
        DB::beginTransaction();

        try {
            $account_code->industryCategories()->detach();
            $account_code->delete();

            Alert::success('Account Code Delete', 'Account Code Successfully deleted');
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::error('Account Code Delete', $exception->getMessage());
        }
        return redirect()->route('master.chart');
    }

    public function prepareDataForAccountCode($request)
    {
        return [
            'industry_category' => $request->industry_category_id,
            'category_id' => $request->category,
            'sub_category_id' => $request->sub_category,
            'additional_category_id' => $request->additional_category,
            'code' => $request->account_code,
            'type' => $request->type,
            'name' => $request->account_name,
            'gst_code' => $request->gst_code,
//            'is_for_all_professions' => $request->is_for_all_professions == null?0:1,
            'note' => $request->note,
        ];
    }
    public function prepareDataForSubCategory($request)
    {
        return [
            'industry_category' => $request->industry_category,
            'parent_id' => $request->category,
            'name' => $request->sub_category_name,
            'code' => $request->single_account_code,
            'note' => $request->note,
            'type' => 2
        ];
    }
    public function prepareDataForAdditionalCategory($request)
    {
        return [
            'industry_category' => $request->industry_category,
            'parent_id' => $request->sub_category,
            'name' => $request->additional_category_name,
            'code' => $request->single_account_code,
            'note' => $request->note,
            'type' => 3
        ];
    }
    public function prepareDataForEditAccountCode($request)
    {
        return[
            'name' => $request->name,
            'note' => $request->note,
        ];
    }

    public function checkPassword($password)
    {
    }
}
