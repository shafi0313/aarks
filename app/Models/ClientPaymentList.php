<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientPaymentList extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    // protected $with = ['client'];
    protected $casts = [
        'started_at' => 'datetime',
        'expire_at'  => 'datetime',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
    public function getDurationAttribute($value)
    {
        return $value . ' Month';
    }
    // public function getPackNameAttribute($value)
    // {
    //     switch ($value) {
    //         case '1':
    //             return 'SIMPLEPAY';
    //             break;
    //         case '2':
    //             return 'ENHANCER';
    //             break;
    //         case '3':
    //             return 'PROCURER';
    //             break;
    //         case '4':
    //             return 'EMPLOYER';
    //             break;
    //         case '5':
    //             return 'PREMIUM';
    //             break;
    //         case '6':
    //             return 'ENTERPRISE';
    //             break;
    //         default:
    //             return "TRT";
    //             break;
    //     }
    // }
    // public function setDurationAttribute($value)
    // {
    //     switch ($value) {
    //         case '1 Month':
    //             $this->attributes['duration'] = '1';
    //             break;
    //         case '2 Month':
    //             $this->attributes['duration'] = '2';
    //             break;
    //         case '3 Month':
    //             $this->attributes['duration'] = '3';
    //             break;
    //         case '6 Month':
    //             $this->attributes['duration'] = '6';
    //             break;
    //         case 'Year':
    //             $this->attributes['duration'] = '12';
    //             break;
    //         default:
    //             return null;
    //             break;
    //     }
    // }
    public function scopeFilter($q)
    {
        if (request()->type == 'approve') {
            $q->where('status', 1)->where('is_expire', 0);
        } else if (request()->type == 'pending') {
            $q->where('status', 0)->where('is_expire', 0);
        } else {
            $q->where('is_expire', 1);
        }
    }
}
