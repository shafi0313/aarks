<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class CheckPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Hash::check($request->password, auth('admin')->user()->getAuthPassword())) {
            Alert::error('Wrong Password', 'Password Doesn\'t match');
            return redirect()->back();
        }
        return $next($request);
    }
}
