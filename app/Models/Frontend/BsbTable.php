<?php

namespace App\Models\Frontend;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Database\Eloquent\Model;

class BsbTable extends Model
{
    protected $guarded = ['id'];

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
