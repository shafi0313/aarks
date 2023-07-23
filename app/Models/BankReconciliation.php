<?php

namespace App\Models;

use App\Models\GeneralLedger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankReconciliation extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $guarded = ["id"];
    public function ledger()
    {
        return $this->belongsTo(GeneralLedger::class);
    }
}
