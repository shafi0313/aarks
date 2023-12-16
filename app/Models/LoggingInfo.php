<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoggingInfo extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function clientUser()
    {
        return $this->belongsTo(Client::class, 'user_id', 'id')->withDefault([
            'name' => 'N/A',
        ]);
    }

    public function adminUser()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id')->withDefault([
            'name' => 'N/A',
        ]);
    }
}
