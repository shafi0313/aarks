<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessPlan extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ["id"];
    protected $dates = ["date"];
    protected $casts = [
        "percent"          => "double",
        "amount"           => "double",
        "old_percent"      => "double",
        "last_year_amount" => "double",
        'date'             => 'datetime'
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
    public function chart()
    {
        return $this->belongsTo(ClientAccountCode::class, "account_code_id", 'id');
    }
}
