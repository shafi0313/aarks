<?php

namespace App\Http\Controllers\Frontend\Sales;

use Illuminate\Http\Request;
use App\Models\Frontend\Dedotr;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Frontend\DedotrPaymentReceive;


class SalesRegisterController extends Controller
{
    public function index()
    {
        return view('frontend.sales.sales-register.select_date');
    }
    public function report(Request $request)
    {
        $client     = client();
        $start_date = makeBackendCompatibleDate($request->from_date);
        $end_date   = makeBackendCompatibleDate($request->to_date);
        $invoices = Dedotr::with(['payments' => function ($q) use ($client) {
            $q->where('client_id', $client->id)->select('*', DB::raw('sum(payment_amount) as sum_pay_amount'));
        }])->where('client_id', $client->id)
            ->where('tran_date', '>=', $start_date->format('Y-m-d'))
            ->where('tran_date', '<=', $end_date->format('Y-m-d'))
            ->where('chart_id', 'not like', '551%')
            ->where('item_name', '!=', '')
            ->where('amount', '!=', 0)->get();
        $payments = DedotrPaymentReceive::where('client_id', $client->id)
            ->where('source', 2)->where('dedotr_inv', null)->sum('payment_amount');
        if ($invoices->count() <= 0) {
            toast('Thene is no data with dates', 'warning');
            return redirect()->back();
        }
        return view('frontend.sales.sales-register.index', compact('client', 'invoices', 'payments'));
    }
}
