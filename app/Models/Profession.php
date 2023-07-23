<?php
namespace App\Models;

use App\Models\Client;
use App\Models\IndustryCategory;
use App\Models\AccountCodeCategory;
use App\Models\ProfessionAccountCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profession extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_professions');
    }

    public function industryCategories()
    {
        return $this->belongsToMany(IndustryCategory::class);
    }

    public function accountCodeCategories()
    {
        return $this->belongsToMany(AccountCodeCategory::class);
    }

    public function getIndustryCategoryNamesAttribute()
    {
        return implode(', ', $this->industryCategories ? $this->industryCategories->pluck('name')->toArray() : []);
    }

    public function accountCodes()
    {
        return $this->hasMany(ProfessionAccountCode::class);
    }
}
