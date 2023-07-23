<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fuel_tax_credit extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    // protected $fillable = [
    //     'financial_year', 'start_date', 'end_date', 'rate',
    // ];
}
