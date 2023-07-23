<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Profession;
use App\Models\GeneralLedger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankReconciliationLedger extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $guarded = ["id"];
    protected $dates = ['date'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
    public function generalLedger()
    {
        return $this->belongsTo(GeneralLedger::class);
    }
}
