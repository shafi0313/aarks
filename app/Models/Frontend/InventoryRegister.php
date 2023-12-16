<?php

namespace App\Models\Frontend;

use App\Models\Client;
use App\Models\Profession;
use App\Models\Frontend\InventoryItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryRegister extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = [
        'date' => 'datetime',
    ];
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
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
