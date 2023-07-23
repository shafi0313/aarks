<?php
namespace App\Models;

use App\Models\Profession;
use App\Models\AccountCode;
use App\Models\IndustryCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountCodeCategory extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function subCategory()
    {
        return $this->hasMany(AccountCodeCategory::class, 'parent_id', 'id')
                ->with('additionalCategory')->orderBy('code', 'asc');
    }

    public function subCategoryWithoutAdditional()
    {
        return $this->hasMany(AccountCodeCategory::class, 'parent_id', 'id');
    }

    public function additionalCategory()
    {
        return $this->hasMany(AccountCodeCategory::class, 'parent_id', 'id')->orderBy('code', 'asc');
    }

    public function industryCategories()
    {
        return $this->belongsToMany(IndustryCategory::class);
    }

    public function professions()
    {
        return $this->belongsToMany(Profession::class);
    }

    public function account_code()
    {
        return $this->hasMany(AccountCode::class);
    }

    public function parent()
    {
        return $this->belongsTo(AccountCodeCategory::class, 'parent_id', 'id');
    }

    public function getUIDAttribute()
    {
        if (is_null($this->parent_id)) {
            return strrev($this->code);
        } else {
            return strrev($this->code . $this->parent->uid);
        }
    }

    public function getIsAdditionalCategoryAttribute()
    {
        return $this->type == 3;
    }

    public function getIsSubCategoryAttribute()
    {
        return $this->type == 2;
    }
}
