<?php
namespace App\Models;

use App\Models\Period;
use App\Models\Service;
use App\Models\Profession;
use App\Models\ClientAccountCode;
use App\Models\ClientPaymentList;
use App\Models\Frontend\BsbTable;
use App\Models\Frontend\ClientLeave;
use App\Models\Frontend\ClientWages;
use App\Models\Frontend\InvoiceLayout;
use Illuminate\Database\Eloquent\Model;
use App\Models\Frontend\ClientDeduction;
use Illuminate\Notifications\Notifiable;
use App\Models\Frontend\ClientSuperannuation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes, Notifiable;

    protected $guarded = ['id'];
    protected $casts = [
        'birthday' => 'datetime',
    ];

    public function professions()
    {
        return $this->belongsToMany(Profession::class, 'client_professions');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
    public function paymentList()
    {
        return $this->hasOne(ClientPaymentList::class);
    }

    public function paylist()
    {
        return $this->hasMany(ClientPaymentList::class);
    }
    public function payment()
    {
        return $this->hasOne(ClientPaymentList::class)->whereStatus(1)->whereIsExpire(0);
    }

    public function periods()
    {
        return $this->hasMany(Period::class);
    }
    public function account_codes()
    {
        return $this->hasMany(ClientAccountCode::class);
    }
    public function wages()
    {
        return $this->hasMany(ClientWages::class);
    }
    public function deduction()
    {
        return $this->hasMany(ClientDeduction::class);
    }
    public function super()
    {
        return $this->hasMany(ClientSuperannuation::class);
    }
    public function leave()
    {
        return $this->hasMany(ClientLeave::class);
    }
    public function invoiceLayout()
    {
        return $this->hasOne(InvoiceLayout::class);
    }
    public function bsb()
    {
        return $this->hasOne(BsbTable::class);
    }

    public function getFullNameAttribute()
    {
        return $this->company??"{$this->first_name} {$this->last_name}";
    }
    public function getAddressAttribute()
    {
        return $this->street_address.', '.$this->state.', '.$this->country.' - '.$this->post_code;
    }
}
