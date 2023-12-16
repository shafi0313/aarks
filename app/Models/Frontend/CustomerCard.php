<?php

namespace App\Models\Frontend;

use App\Models\Client;
use App\Models\Profession;
use App\Models\Frontend\Dedotr;
use App\Models\Frontend\Creditor;
use Illuminate\Database\Eloquent\Model;
use App\Models\Frontend\DedotrPaymentReceive;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Frontend\CreditorPaymentReceive;

class CustomerCard extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'opening_blnc_date' => 'datetime',
        'by_date'           => 'datetime',
        'after_date'        => 'datetime'
    ];

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function dedotrs()
    {
        return $this->hasMany(Dedotr::class, 'customer_card_id', 'id');
    }
    public function dedotrPayments()
    {
        return $this->hasMany(DedotrPaymentReceive::class);
    }
    public function creditors()
    {
        return $this->hasMany(Creditor::class);
    }
    public function creditorPayments()
    {
        return $this->hasMany(CreditorPaymentReceive::class);
    }
}
