<?php

namespace App\Models;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PeriodLock extends Model
{
    protected $guarded = [];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function getDateAttribute($val)
    {
        return Carbon::parse($val)->format('d/m/Y');
    }
}
