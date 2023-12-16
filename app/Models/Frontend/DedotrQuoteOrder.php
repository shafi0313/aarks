<?php

namespace App\Models\Frontend;

use App\Models\Client;
use App\Models\Frontend\CustomerCard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DedotrQuoteOrder extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'tran_date'  => 'datetime',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function customer()
    {
        return $this->belongsTo(CustomerCard::class, 'customer_card_id');
    }
}
