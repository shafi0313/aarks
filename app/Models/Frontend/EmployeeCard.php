<?php

namespace App\Models\Frontend;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Database\Eloquent\Model;

class EmployeeCard extends Model
{
    protected $guarded = ['id'];
    protected $dates = [
        'dob', 'start_date', 'term_date'
    ];

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
