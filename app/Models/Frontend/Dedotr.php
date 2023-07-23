<?php

namespace App\Models\Frontend;

use App\Models\Client;
use App\Models\ClientAccountCode;
use App\Models\Profession;
use App\Models\Frontend\CustomerCard;
use App\Models\Frontend\InventoryItem;
use Illuminate\Database\Eloquent\Model;
use App\Models\Frontend\DedotrPaymentReceive;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dedotr extends Model
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
    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_no');
    }
    public function customer()
    {
        return $this->belongsTo(CustomerCard::class, 'customer_card_id')->withDefault([
            'name' => 'N/A',
        ]);
    }
    public function payments()
    {
        return $this->hasMany(DedotrPaymentReceive::class, 'dedotr_inv', 'inv_no');
    }
    public function trashedPayments()
    {
        return $this->hasMany(DedotrPaymentReceive::class, 'dedotr_inv', 'inv_no')->onlyTrashed();
    }

    // Local Scope
    public function scopeFilter($q)
    {
        if (request()->has('start_date') && request()->has('end_date')) {
            $start_date = makeBackendCompatibleDate(request()->start_date);
            $end_date   = makeBackendCompatibleDate(request()->end_date);
            $q->where('tran_date', '>=', $start_date)
            ->where('tran_date', '<=', $end_date);
        }
        $q->latest('tran_date');
        return $q;
    }
}
