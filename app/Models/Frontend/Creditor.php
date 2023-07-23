<?php

namespace App\Models\Frontend;

use App\Models\Client;
use App\Models\ClientAccountCode;
use App\Models\Frontend\CustomerCard;
use App\Models\Frontend\InventoryItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Frontend\CreditorPaymentReceive;

class Creditor extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    // protected $with = ['client','customer'];
    protected $dates = ['tran_date'];

    public function clientAccountCode()
    {
        return $this->belongsTo(ClientAccountCode::class, 'chart_id', 'code');
    }
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
    public function payments()
    {
        return $this->hasMany(CreditorPaymentReceive::class, 'creditor_inv', 'inv_no');
    }
}
