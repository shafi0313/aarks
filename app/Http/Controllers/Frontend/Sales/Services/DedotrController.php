<?php

namespace App\Http\Controllers\Frontend\Sales\Services;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Frontend\Dedotr;
use App\Models\ClientAccountCode;
use App\Models\Frontend\Dedotr_job;
use App\Models\Frontend\Dedotr_qtc;
use App\Http\Controllers\Controller;
use App\Models\Frontend\CustomerCard;


class DedotrController extends Controller
{
    public function index()
    {
        $client    = client();
        $customers = CustomerCard::where('client_id', $client->id)->get();
        $dedotrs   = Dedotr::where('client_id', $client->id)->get();
        $codes     = ClientAccountCode::where('client_id', $client->id)
                    ->where(function ($q) {
                        $q->where('code', 'like', '1%')
                        ->orWhere('code', 'like', '2%')
                        ->orWhere('code', 'like', '5%')
                        ->orWhere('code', 'like', '9%');
                    })
                    ->where('type', '2')
                    ->orderBy('code')
                    ->get();
        return view('frontend.sales.quote.service_quote', compact('client', 'customers', 'dedotrs', 'codes'));
    }
    public function templateStore(Request $request)
    {
        $data = $request->validate([
            "client_id" => 'required|integer',
            "title"     => 'required|string',
            "details"   => 'required|string',
        ]);
        try {
            $data = Dedotr_qtc::create($data);
            return response()->json(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function templateShow()
    {
        $client = Client::find(client()->id);
        $dedotrs = Dedotr_qtc::where('client_id', $client->id)->get();
        return response()->json(['status'=>200,'dedotrs'=>$dedotrs]);
    }
    public function templateDelete(Request $r)
    {
        Dedotr_qtc::findOrFail($r->id)->delete();
        return response()->json(['status'=>200,'message'=>'Template Deelete']);
    }

    public function jobStore(Request $request)
    {
        $data = $request->validate([
            "client_id"              => 'required|integer',
            "title"                  => 'required|string',
            "details"                => 'required|string',
            "client_account_code_id" => 'required|integer',
        ]);
        $client  = Client::find($request->client_id);
        $code = ClientAccountCode::find($request->client_account_code_id);
        if ($client->is_gst_enabled == 1 && ($code->gst_code == 'GST' || $code->gst_code == 'CAP' || $code->gst_code == 'INP')) {
            $data['is_tax'] = 'yes';
        } else {
            $data['is_tax'] = 'no';
        }
        try {
            $data = Dedotr_job::create($data);
            return response()->json(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function jobShow()
    {
        $client = Client::find(client()->id);
        $jobs = Dedotr_job::where('client_id', $client->id)->get();
        return response()->json(['status'=>200,'jobs'=>$jobs]);
    }
    public function jobDelete(Request $r)
    {
        Dedotr_job::findOrFail($r->id)->delete();
        return response()->json(['status'=>200,'message'=>'Template Delete']);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "client_id"        => 'required',
            "customer_card_id" => 'required',
            "tran_date"       => 'required',
            "inv_no"           => 'required',
            "your_ref"         => 'required',
            "quote_terms"      => 'required',
            "job_title"        => 'required',
            "job_des"          => 'required',
            "price"            => 'required',
            "disc_rate"        => 'required',
            "freight_charge"   => 'required',
            "chart_id"         => 'required'
        ]);
        $data['start_date'] = makeBackendCompatibleDate($request->start_date);
        $data['tax_rate'] =10;
        $price = ($request->price * ($request->disc_rate/100))+$request->freight_charge;
        if ($request->is_tax == 'yes') {
            $data['amount'] = $price+($request->price/11);
        } else {
            $data['amount'] = $price;
        }
        // try {
        //     Dedotr::create($data);
        //     toast('Dedotr Create success', 'success');
        // } catch (\Exception $e) {
        //     toast($e->getMessage(), 'error');
        // }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Frontend\Dedotr  $dedotr
     * @return \Illuminate\Http\Response
     */
    public function show(Dedotr $dedotr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Frontend\Dedotr  $dedotr
     * @return \Illuminate\Http\Response
     */
    public function edit(Dedotr $dedotr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Frontend\Dedotr  $dedotr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dedotr $dedotr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Frontend\Dedotr  $dedotr
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dedotr $dedotr)
    {
        try {
            $dedotr->delete();
            toast('dedotr deleted success', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
}
