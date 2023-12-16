<?php

namespace App\Models\Frontend;

use App\Models\Frontend\EmployeeCard;
use Illuminate\Database\Eloquent\Model;

class PreparePayroll extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'payment_date'    => 'datetime',
        'payperiod_start' => 'datetime',
        'payperiod_end'   => 'datetime',
    ];
    
    public function employee_card()
    {
        return $this->belongsTo(EmployeeCard::class);
    }
}
