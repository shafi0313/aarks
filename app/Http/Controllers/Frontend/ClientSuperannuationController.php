<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StandardSuperannuation;
use App\Models\Frontend\ClientSuperannuation;

class ClientSuperannuationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supperAnnuations = StandardSuperannuation::all();
        $clientAnnuations = ClientSuperannuation::where('client_id',client()->id)->get();
        return view('frontend.payroll.manage_super.index',compact('supperAnnuations','clientAnnuations'));
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
            "name"       => 'required|string',
            "e_rate"     => 'sometimes',
            "e_tools"    => 'sometimes',
            "e_fix_amt"  => 'sometimes',
            "e_period"   => 'sometimes',
            "e_excl_amt" => 'sometimes',
            "t_rate"     => 'sometimes',
            "t_tools"    => 'sometimes',
            "t_fix_amt"  => 'sometimes',
            "t_period"   => 'sometimes',
            "t_excl_amt" => 'sometimes',
            "client_id" => 'required'
        ]);

        try {
            ClientSuperannuation::create($data);
            toast('Superannuation Crated!', 'success');
        } catch (\Throwable $th) {
            toast($th->getMessage(), 'error');
        }
        return back();

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Frontend\ClientSuperannuation  $clientannuation
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientSuperannuation $clientannuation)
    {
        return view('frontend.payroll.manage_super.update', compact('clientannuation'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Frontend\ClientSuperannuation  $clientannuation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientSuperannuation $clientannuation)
    {
        $data = $this->validate($request, [
            "name"       => 'required|string',
            "e_rate"     => 'sometimes',
            "e_tools"    => 'sometimes',
            "e_fix_amt"  => 'sometimes',
            "e_period"   => 'sometimes',
            "e_excl_amt" => 'sometimes',
            "t_rate"     => 'sometimes',
            "t_tools"    => 'sometimes',
            "t_fix_amt"  => 'sometimes',
            "t_period"   => 'sometimes',
            "t_excl_amt" => 'sometimes',
        ]);

        try {
            $clientannuation->update($data);
            toast('Superannuation Updated!', 'success');
        } catch (\Throwable $th) {
            toast($th->getMessage(), 'error');
        }

        return redirect()->route('clientannuation.index');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Frontend\ClientSuperannuation  $clientannuation
     * @return \Illuminate\Http\Response
     */
    public function delete(ClientSuperannuation $clientannuation)
    {
        try {
            $clientannuation->delete();
            toast('Superannuation Deleted!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();

    }
}
