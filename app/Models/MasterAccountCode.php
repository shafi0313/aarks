<?php
namespace App\Models;

use App\Models\AccountCode;
use App\Models\IndustryCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterAccountCode extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
//    public function accountCode()
//    {
//        return $this->belongsTo(AccountCode::class)->whereNull('deleted_at')->orderBy('code');
//    }

    public function industryCategories()
    {
        return $this->belongsToMany(IndustryCategory::class, 'master_account_code_industry_category');
    }
}
