<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Profession;
use App\Models\ClientAccountCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntry extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
    public function client_account_code()
    {
        return $this->belongsTo(ClientAccountCode::class, 'account_code', 'id');
    }
    protected $casts = [
        'date'=>'datetime',
    ];
}
