<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = ['id'];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class);
    }
}
