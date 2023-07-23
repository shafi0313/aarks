<?php

namespace App\Models;

use App\Models\Client;
use App\Models\ClientAccountCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gsttbl extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function accountCodes()
    {
        return $this->hasOne(ClientAccountCode::class, 'code', 'chart_code');
    }
}
