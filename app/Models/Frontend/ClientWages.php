<?php

namespace App\Models\Frontend;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

class ClientWages extends Model
{
    protected $guarded = ['id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
