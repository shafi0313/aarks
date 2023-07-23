<?php

namespace App\Models\Frontend;

use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use App\Models\ClientAccountCode;
use App\Models\Frontend\CashOffice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashBook extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function professions()
    {
        return $this->belongsToMany(Profession::class);
    }
    public function accountCode()
    {
        return $this->belongsTo(ClientAccountCode::class, 'chart_id', 'code');
    }
    public function clients()
    {
        return $this->belongsToMany(Client::class);
    }
    public function office()
    {
        return $this->belongsTo(CashOffice::class);
    }
    public function periods()
    {
        return $this->hasMany(Period::class);
    }
    protected $casts = ['tran_date'=>'datetime'];
}
