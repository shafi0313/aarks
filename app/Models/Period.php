<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Period extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = [
        'date'       => 'datetime',
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function profession()
    {
        return $this->belongsTo(Profession::class)->withDefault([
            'name' => 'Not Found'
        ]);
    }
}
