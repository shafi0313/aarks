<?php

namespace App\Models;

use App\Models\Profession;
use App\Models\IndustryCategory;
use App\Models\AccountCodeCategory;
use Illuminate\Database\Eloquent\Model;

class ProfessionAccountCode extends Model
{
    protected $table = 'account_code_profession';
    protected $guarded = ['id'];

    public function account_code_category()
    {
        return $this->belongsTo(AccountCodeCategory::class);
    }

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
    public function industryCategories()
    {
        return $this->belongsToMany(IndustryCategory::class)->whereNull('account_code_industry_category.deleted_at')->withTimestamps();
    }
}
