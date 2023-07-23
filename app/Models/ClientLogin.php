<?php
namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\CausesActivity;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ClientLogin extends Authenticatable
{
    use HasRoles, CausesActivity;

    protected $table   = 'clients';
    protected $guard   = 'admin';
    protected $guarded = ['id'];
    protected $hidden  = ['password', 'remember_token'];

    // public function toggleActiveStatus()
    // {
    //     $status = $this->is_active;
    //     $this->is_active = !$status;
    //     $this->save();
    //     return $this;
    // }
}
