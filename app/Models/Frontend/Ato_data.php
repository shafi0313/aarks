<?php

namespace App\Models\Frontend;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

class Ato_data extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'payment_date' => 'datetime'
    ];
    protected $with = ['client'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
