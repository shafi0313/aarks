<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\GeneralLedger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientAccountCode extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function generalLedger()
    {
        return $this->hasMany(GeneralLedger::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getGeneralLedgerOfDate($date)
    {
        $first_date = GeneralLedger::where('client_account_code_id', $this->id)
                ->whereDate('date', '<=', $date)
                ->orderBy('date', 'desc')
                ->first();
        if ($first_date) {
            return GeneralLedger::where('client_account_code_id', $this->id)
                ->whereDate('date', $first_date->date)
                ->orderBy('id', 'desc')
                ->get();
        }
        return null;
    }
}
