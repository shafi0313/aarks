<?php
namespace App\Models;

use App\Models\Client;
use App\Models\Transaction;
use App\Models\ClientAccountCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneralLedger extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $dates = ['date'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function client_account_code()
    {
        return $this->belongsTo(ClientAccountCode::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'transaction_id');
    }

    public function getNetAmountAttribute()
    {
        return $this->credit == 0 ? $this->debit : $this->credit;
    }

    public function getIsDebitAttribute()
    {
        return $this->debit ? true : false;
    }
}
