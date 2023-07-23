<?php

namespace App\Http\Middleware\Custom;

use Closure;
use App\Models\Client;
use App\ClientPaymentList;

class SubsCheck
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
            $client = Client::with(['paylist' => function ($q) {
                $q->where('is_expire', 0)->where('status', 1)->latest();
            }])->findOrFail(client()->id);
            $paylist = $client->paylist->first();
            if ($paylist != null) {
                return $next($request);
            }
            toast('You don\'t have access', 'error');
            return redirect()->route('index');
        }
        // return abort(404);
    }
}
