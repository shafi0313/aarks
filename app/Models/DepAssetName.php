<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Depreciation;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepAssetName extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'purchase_date' => 'datetime',
        'owdv_value_date' => 'datetime',
        'disposal_date' => 'datetime',
    ];

    public function depreciation()
    {
        return $this->belongsTo(Depreciation::class);
    }

    public function transactions()
    {
        return DB::table('assetname_transaction')->where('dep_asset_name_id', $this->id)->pluck('tran_id')->toArray();
    }

    public function getDepMethodAttribute($dep_method)
    {
        if ($dep_method == 1) {
            return 'D';
        } elseif ($dep_method == 2) {
            return 'P';
        } else {
            return 'W';
        }
    }
    public function setDepMethodAttribute($dep_method)
    {
        if ($dep_method == 'D') {
            $this->attributes['dep_method'] =  1;
        } elseif ($dep_method == 'P') {
            $this->attributes['dep_method'] =  2;
        } else {
            $this->attributes['dep_method'] =  3;
        }
    }
}
