<?php

namespace App\Models;

use App\Models\Profession;
use App\Models\IndustryCategory;
use App\Models\MasterAccountCode;
use App\Models\AccountCodeCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountCode extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function account_code_category()
    {
        return $this->belongsTo(AccountCodeCategory::class);
    }

    public function masterAccountCode()
    {
        return $this->hasMany(MasterAccountCode::class);
    }

    public function professions()
    {
        return $this->belongsToMany(Profession::class)->whereNull('account_code_profession.deleted_at')->withTimestamps();
    }

    public function industryCategories()
    {
        return $this->belongsToMany(IndustryCategory::class)->whereNull('account_code_industry_category.deleted_at')->withTimestamps();
    }
}
