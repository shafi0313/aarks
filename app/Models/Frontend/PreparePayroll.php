<?php

namespace App\Models\Frontend;

use App\Models\Frontend\EmployeeCard;
use Illuminate\Database\Eloquent\Model;

class PreparePayroll extends Model
{
    protected $guarded = ['id'];

    protected $dates = [
        'payment_date',
        'payperiod_start',
        'payperiod_end',
    ];
    public function employee_card()
    {
        return $this->belongsTo(EmployeeCard::class);
    }
}
