<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function activities()
    {
        return $this->hasMany(ActivityLog::class, 'causer_id', 'user_id')->latest();
    }
}
