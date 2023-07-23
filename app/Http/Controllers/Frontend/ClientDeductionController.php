<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\StandardDeducation;
use App\Http\Controllers\Controller;
use App\Models\Frontend\ClientDeduction;

class ClientDeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stanDeducs = StandardDeducation::all();
        $deducs = ClientDeduction::where('client_id',client()->id)->get();
        return view('frontend.payroll.manage_deducation.index',compact('deducs','stanDeducs'));

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $this->validate($request, [
            "name"    => 'required|string',
            "rate"    => 'sometimes',
            "tools"   => 'sometimes',
            "fix_amt" => 'sometimes',
            "period"  => 'sometimes',
            "limit"   => 'sometimes',
            "client_id"   => 'required',
        ]);

        try {
            ClientDeduction::create($data);
            toast('Deduction Created!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Frontend\ClientDeduction  $clientdeduction
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientDeduction $clientdeduction)
    {
        return view('frontend.payroll.manage_deducation.update',compact('clientdeduction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Frontend\ClientDeduction  $clientdeduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientDeduction $clientdeduction)
    {
        $data = $this->validate($request, [
            "name"    => 'required|string',
            "rate"    => 'sometimes',
            "tools"   => 'sometimes',
            "fix_amt" => 'sometimes',
            "period"  => 'sometimes',
            "limit"   => 'sometimes',
            "client_id"   => 'sometimes',
        ]);

        try {
            $clientdeduction->update($data);
            toast('Clientdeduction Updated!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return redirect()->route('clientdeduction.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Frontend\ClientDeduction  $clientdeduction
     * @return \Illuminate\Http\Response
     */
    public function delete(ClientDeduction $clientdeduction)
    {
        try {
            $clientdeduction->delete();
            toast('Deducation Deleted!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();

    }
}
