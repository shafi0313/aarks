<?php

namespace App\Models\Frontend;

use App\Models\ClientAccountCode;
use Illuminate\Database\Eloquent\Model;

class Dedotr_job extends Model
{
    protected $guarded = ['id'];
    protected $with = ['code'];
    public function code()
    {
        return $this->belongsTo(ClientAccountCode::class, 'client_account_code_id');
    }
}
