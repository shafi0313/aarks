<?php

namespace App\Models\Frontend;

use App\Models\Client;
use App\Models\Frontend\EmployeeCard;
use Illuminate\Database\Eloquent\Model;

class PayAccumAmt extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'tran_date'       => 'datetime',
        'payment_date'    => 'datetime',
        'payperiod_start' => 'datetime',
        'payperiod_end'   => 'datetime',
    ];
    protected $with = ['client', 'employee'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    public function employee()
    {
        return $this->belongsTo(EmployeeCard::class, 'employee_card_id');
    }
}
