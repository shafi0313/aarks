<?php

namespace App\Http\Middleware\Custom;

use Closure;
use App\Models\VisitorInfo;

class VisitorInfoMiddleware
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
        $ip = geoip()->getClientIP();
        $geoInfo = geoip()->getLocation($ip);
        $data = [
            "ip"          => $geoInfo->ip,
            "iso_code"    =>$geoInfo->iso_code,
            "country"     =>$geoInfo->country,
            "city"        =>$geoInfo->city,
            "state_name"  =>$geoInfo->state_name,
            "postal_code" =>$geoInfo->postal_code,
            "lat"         =>$geoInfo->lat,
            "lon"         =>$geoInfo->lon,
            "currency"    =>$geoInfo->currency,
        ];
        $visitor = VisitorInfo::where('ip', $geoInfo->ip)->first();
        if ($visitor == null) {
            VisitorInfo::create($data);
        } else {
            $visitor->update($data);
        }

        return $next($request);
    }
}
