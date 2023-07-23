<?php

namespace App\Models\Frontend;

use App\Models\Frontend\InventoryItem;
use Illuminate\Database\Eloquent\Model;

class InventoryCategory extends Model
{
    protected $guarded = ['id'];

    public function subcat()
    {
        return $this->hasMany(InventoryCategory::class, 'parent_id', 'id');
    }
    public function items()
    {
        return $this->hasMany(InventoryItem::class, 'category', 'name');
    }
}
