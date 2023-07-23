<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\SubCategoryCreateRequest;
use App\Actions\AccountCodeActions\AddSubCategory;
use App\Http\Requests\AdditionalCategoryCreateRequest;
use App\Actions\AccountCodeActions\AddAdditionalCategory;

class AccountCodeCategoryController extends Controller
{
    public function store(SubCategoryCreateRequest $request, AddAdditionalCategory $addSubCategory)
    {
        if ($error = $this->sendPermissionError('admin.account-code.sub-category.create')) {
            return $error;
        }

        $data = $this->prepareDataForSubCategory($request);
        DB::beginTransaction();

        try {
            $addSubCategory->setData($data)->execute();
            Alert::success('Sub Group', 'Sub Group Created Successfully');
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::error('Sub Group', $exception->getMessage());
        }

        return redirect()->route('account-code.index', $request->profession_id);
    }
    public function generateAdditionalCategory(AdditionalCategoryCreateRequest $request, AddAdditionalCategory $addAdditionalCategory)
    {
        if ($error = $this->sendPermissionError('admin.account-code.additional-category.create')) {
            return $error;
        }

        $data = $this->prepareDataForAdditionalCategory($request);
        DB::beginTransaction();

        try {
            $addAdditionalCategory->setData($data)->execute();
            Alert::success('Sub Sub Group', 'Sub Sub Group Created Successfully');
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::error('Sub Group', $exception->getMessage());
        }

        return redirect()->route('account-code.index', $request->profession_id);
    }

    public function prepareDataForSubCategory($request)
    {
        return [
            'profession_id' => $request->profession_id,
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
            'profession_id' => $request->profession_id,
            'industry_category' => $request->industry_category,
            'parent_id' => $request->sub_category,
            'name' => $request->additional_category_name,
            'code' => $request->single_account_code,
            'note' => $request->note,
            'type' => 3
        ];
    }
}
