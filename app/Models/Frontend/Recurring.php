<?php

namespace App\Models\Frontend;

use App\Models\Client;
use App\Models\Frontend\CustomerCard;
use App\Models\Frontend\InventoryItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recurring extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = [
        'tran_date'   => 'datetime',
        'untill_date' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function customer()
    {
        return $this->belongsTo(CustomerCard::class, 'customer_card_id');
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_no');
    }
}
