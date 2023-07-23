<?php

namespace App\Models;

use App\Models\ClientPaymentList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;
    protected $guarded = ["id"];
    public function plans()
    {
        return $this->hasMany(ClientPaymentList::class);
    }
}
