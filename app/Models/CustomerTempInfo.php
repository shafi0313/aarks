<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Profession;
use App\Models\Frontend\CustomerCard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerTempInfo extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $guarded = ["id"];

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function customer()
    {
        return $this->belongsTo(CustomerCard::class);
    }
}
