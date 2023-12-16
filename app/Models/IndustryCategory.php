<?php

namespace App\Models;

use App\Models\MasterAccountCode;
use App\Models\AccountCodeCategory;
use Illuminate\Database\Eloquent\Model;

class IndustryCategory extends Model
{
    protected $guarded = ['id'];

    public function accountCodeCategories()
    {
        return $this->belongsToMany(AccountCodeCategory::class);
    }

    public function scopeParentCategoryWithSubCategory($query)
    {
        return $query->with([
            'accountCodeCategories' => function ($account_code_query) {
                $account_code_query->where('type', 1)->with('subCategory');
            }
        ]);
    }

    public function masterAccountCodes()
    {
        return $this->belongsToMany(MasterAccountCode::class, 'master_account_code_industry_category');
    }
}
