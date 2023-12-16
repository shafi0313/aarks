<?php

namespace App\Models;

use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['id'];
    protected $dates = ['transaction_date'];

    public function general_ledger()
    {
        return $this->belongsTo(GeneralLedger::class);
    }
    public function client_account_code()
    {
        return $this->belongsTo(ClientAccountCode::class);
    }
}
