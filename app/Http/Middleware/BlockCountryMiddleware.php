<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockCountryMiddleware
{
    public $blockCountries = ['China', 'India', 'Russia', 'Japan', 'Brazil', 'Hong Kong', 'South Korea'];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $geoInfo = geoip()->getLocation(geoip()->getClientIP());

        if (in_array($geoInfo->country, $this->blockCountries)) {
            abort(403, "You are restricted to access the site.");
        }
        return $next($request);
    }
}
