<?php

namespace App\Http\Controllers\Calendar;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\Frontend\Dedotr;
use App\Models\ClientAccountCode;
use App\Http\Controllers\Controller;
use App\Models\Frontend\CustomerCard;

class InvoiceController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('calendar.invoice.select_activity', compact('client'));
    }

    public function create(Profession $profession)
    {
        $client  = Client::find(client()->id);
        $payment  = $client->payment;
        $quation = Dedotr::whereClientId($client->id)->whereBetween('tran_date', [$payment->started_at->format('Y-m-d'), $payment->expire_at->format('Y-m-d')])->count();
        // if ($quation > $payment->invoice) {
        //     toast('Invoice limit reached.', 'error');
        //     return redirect()->back();
        // }
        $customers = CustomerCard::where('client_id', $client->id)->where('profession_id', $profession->id)->where('type', 1)->orderBy('name')->get();
        $codes     = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', 'like', '1%')
            // ->where('type', '2')
            ->orderBy('code')
            ->get();

        $liquid_codes = ClientAccountCode::where('additional_category_id', aarks('liquid_asset_id'))
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('code', 'asc')
            ->get();

        return view('calendar.invoice.service_invoice', compact('client', 'customers', 'codes', 'profession', 'liquid_codes'));
    }

    public function getTax(Request $request)
    {
        $code   = ClientAccountCode::find($request->chart_id);
        $client = Client::find(client()->id);
        if ($client->is_gst_enabled == 1 && ($code->gst_code == 'GST' || $code->gst_code == 'CAP' || $code->gst_code == 'INP')) {
            $tax = 'yes';
        } else {
            $tax = 'no';
        }
        return response()->json(['tax' => $tax]);
    }
}
