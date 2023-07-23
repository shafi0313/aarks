<?php

namespace App\Http\Controllers\Frontend\Purchase;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Frontend\Creditor;
use \PDF;
use App\Http\Controllers\Controller;

class PurchaseRegisterController extends Controller
{
    public function index()
    {
        return view('frontend.purchase.purchase-register.select_date');
    }
    public function report(Request $request)
    {
        $client     = client();
        $start_date = makeBackendCompatibleDate($request->from_date);
        $end_date   = makeBackendCompatibleDate($request->to_date);
        $invoices   = Creditor::where('client_id', $client->id)
            ->where('tran_date', '>=', $start_date->format('Y-m-d'))
            ->where('tran_date', '<=', $end_date->format('Y-m-d'))
            ->where('chart_id', 'not like', '551%')
            ->where('item_name', '!=', '')
            ->orderBy('tran_date')
            ->get();
        if ($invoices->count() <= 0) {
            toast('Thene is no data with dates', 'warning');
            return redirect()->back();
        }
        return view('frontend.purchase.purchase-register.index', compact('client', 'invoices'));
    }
}
