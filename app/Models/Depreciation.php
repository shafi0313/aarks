<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Profession;
use App\Models\DepAssetName;
use App\Models\ClientAccountCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Depreciation extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
    public function asset_names()
    {
        return $this->hasMany(DepAssetName::class);
    }

    public function da_code()
    {
        return $this->belongsTo(ClientAccountCode::class, 'dep_acc', 'id');
    }
    public function ada_code()
    {
        return $this->belongsTo(ClientAccountCode::class, 'accm_dep_acc', 'id');
    }
}
