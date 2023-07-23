<?php

namespace App\Http\Controllers;

use App\Models\Payg;
use App\Models\Client;
use App\Models\Period;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class PaygController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        $payg = Payg::where('client_id', $r->client_id)->where('period_id', $r->period_id)->first();
        if ($payg != '') {
            return json_encode(['status' => 'success', 'data' => $payg]) ;
        } else {
            return json_encode(['status'=>'error']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        $client = Client::findOrFail($r->clientId);
        $period = Period::findOrFail($r->periodId);

        $payg_percenttige = $r->payg_percenttige ;
        $payg_amount = $r->payg_amount ;

        $data=[
                'client_id' => $client->id,
                'period_id' => $period->id,
                'percent'   => $payg_percenttige,
                'amount'    => $payg_amount,
            ];

        // Check Period Lock
        if (periodLock($client->id, $period->end_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        try {
            $payg = Payg::where('client_id', $r->clientId)->where('period_id', $r->periodId)->first();
            if ($payg == null) {
                Payg::create($data);
            } else {
                Payg::where('client_id', $r->clientId)->where('period_id', $r->periodId)->update($data) ;
            }
            return 1;
        } catch (\Exception $exception) {
            return 0;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
