<?php

namespace App\Http\Middleware\Custom;

use Closure;
use App\Models\Client;
use App\Frontend\Payslip;

class PackChecker
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
        $client = Client::with(['paylist'=>function ($q) {
            $q->where('is_expire', 0)->where('status', 1)->latest();
        }])->findOrFail(client()->id);
        $paylist = $client->paylist->first();
        $payslip = Payslip::where('client_id', $client->id)
                ->whereBetween('created_at', [$paylist->created_at,$paylist->created_at->addMonth($paylist->duration)])
                ->get();
        if ($paylist->pack_name == 1) {
            if ($payslip->count() >= ($paylist->duration*2)) {
                toast('You can\'t create more than '.($paylist->duration*2).' payslip. Please Update package', 'warning');
                return redirect()->route('upgrade');
            }
        }
        return $next($request);

    }
}
