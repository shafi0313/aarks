<?php

namespace App\Http\Middleware\Custom;

use Closure;

class ClientAuth
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
        if (auth()->guard('client')->check()) {
            return $next($request);
        }
        return abort(403);
    }
}
