<?php
namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\CausesActivity;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Admin extends Authenticatable  implements Auditable
{
    use SoftDeletes, HasRoles, CausesActivity;
    use \OwenIt\Auditing\Auditable;

    protected $guard   = 'admin';
    protected $guarded = ['id'];
    protected $hidden  = ['password', 'remember_token','two_factor_secret','two_factor_recovery_codes'];

    public function toggleActiveStatus()
    {
        $status = $this->is_active;
        $this->is_active = !$status;
        $this->save();
        return $this;
    }

    protected $dates = ['last_login_at'];
    // public function setTwoFactorSecretAttribute($value)
    // {
    //      $this->attributes['two_factor_secret'] = encrypt($value);
    // }

    // public function getTwoFactorSecretAttribute($value)
    // {
    //     return decrypt($value);
    // }
}
