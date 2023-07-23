<?php

namespace App\Models\Frontend;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

class InvoiceLayout extends Model
{
    protected $guarded = ['id'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
