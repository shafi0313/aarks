<?php

namespace App\Models\Frontend;

use App\Models\Client;
use App\Models\Frontend\PayAccumAmt;
use App\Models\Frontend\EmployeeCard;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    protected $guarded = ['id'];
    protected $with = ['client', 'employee', 'pay_accum'];
    protected $casts = [
        'tran_date' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function employee()
    {
        return $this->belongsTo(EmployeeCard::class, 'employee_card_id');
    }
    public function pay_accum()
    {
        return $this->belongsTo(PayAccumAmt::class, 'pay_accum_amt_id');
    }
}
