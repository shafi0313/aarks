<?php

namespace App\Models;

use App\Models\Client;
use App\Models\ClientAccountCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankReconciliationAdmin extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ["id"];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function client_account_code()
    {
        return $this->belongsTo(ClientAccountCode::class, 'chart_id', 'code');
    }
}
