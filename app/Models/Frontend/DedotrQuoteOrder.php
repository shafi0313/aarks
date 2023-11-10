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
    
    protected $dates = ['start_date','end_date','tran_date'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function customer()
    {
        return $this->belongsTo(CustomerCard::class, 'customer_card_id');
    }
}
