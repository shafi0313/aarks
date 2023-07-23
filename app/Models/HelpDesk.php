<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HelpDesk extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $guarded = ["id"];
    public function subCategories()
    {
        return $this->hasMany(HelpDesk::class, 'parent_id');
    }
}
