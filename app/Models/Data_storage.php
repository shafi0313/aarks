<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Data_storage extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function perios()
    {
        return $this->has(Period::class);
    }
    public function client_account_code()
    {
        return $this->belongsTo(ClientAccountCode::class, 'chart_id', 'code');
    }
    protected $casts = ['trn_date'=>'datetime'];
}
