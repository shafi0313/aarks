<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Period;
use App\Models\ClientProfession;
use App\Models\Frontend\Recurring;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Frontend\CustomerCard;
use App\Models\PeriodLock;
use RealRashid\SweetAlert\Facades\Alert;

class IndexController extends Controller
{
    public function index()
    {
        if (auth()->guard('client')->check()) {
            $card              = CustomerCard::whereClient_id(client()->id);
            $data['customer']  = $card->whereType(1)->count();
            $data['supplier']  = $card->whereType(2)->count();
            $data['recurring'] = Recurring::whereClient_id(client()->id)
                ->whereIs_expire(0)
                ->selectRaw('tran_id, count(*) as totalRecurring')
                ->groupBy('tran_id')
                ->pluck('totalRecurring', 'tran_id')->count();
            $data['periods'] = Period::whereClient_id(client()->id)
                ->orderBy('end_date', 'desc')
                ->get()
                ->groupBy('profession_id');
            $data['profession'] = ClientProfession::whereClient_id(client()->id)->count();
            $data['periodLock'] = PeriodLock::whereClient_id(client()->id)->first();
            $data['period_alert'] = Period::whereClient_id(client()->id)
                ->orderBy('end_date', 'desc')
                ->get()
                ->groupBy('profession_id')
                ->map(function ($item, $key) {
                    return $item->first();
                });

            foreach ($data['period_alert'] as $value) {
                if($value->end_date < date('Y-m-d')){
                    Alert::warning('Period Alert', 'Your period date is expired');
                }
            }
            return view('frontend.index', $data);
        }
        return view('frontend.index');
    }
}
