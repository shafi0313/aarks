<?php

namespace App\Models\Frontend;

use App\Models\Client;
use App\Models\Profession;
use App\Models\Frontend\Dedotr;
use App\Models\Frontend\CustomerCard;
use App\Models\Frontend\InventoryItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DedotrTempPaymentReceive extends Model
{
    use SoftDeletes;
    protected $guarded = ["id"];
    protected $casts = [
        'tran_date' => 'datetime',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_no');
    }
    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
    public function dedotr()
    {
        return $this->belongsTo(Dedotr::class);
    }
    public function customer()
    {
        return $this->belongsTo(CustomerCard::class, 'customer_card_id');
    }
}
