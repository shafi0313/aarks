<?php

namespace App\Models\Frontend;

use App\Models\Client;
use App\Models\Profession;
use App\Models\Frontend\Measure;
use App\Models\ClientAccountCode;
use App\Models\Frontend\CustomerCard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
    public function measure()
    {
        return $this->belongsTo(Measure::class);
    }
    public function customer()
    {
        return $this->belongsTo(CustomerCard::class, 'customer_card_id');
    }
    public function code()
    {
        return $this->belongsTo(ClientAccountCode::class, 'client_account_code_id');
    }
}
