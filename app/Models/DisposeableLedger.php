<?php

namespace App\Models;

use App\Models\GeneralLedger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisposeableLedger extends Model
{
    use SoftDeletes;

    protected $guarded = ["id"];
    public $timestamps = false;
    public function ledger()
    {
        return $this->belongsTo(GeneralLedger::class, 'general_ledger_id');
    }
}
