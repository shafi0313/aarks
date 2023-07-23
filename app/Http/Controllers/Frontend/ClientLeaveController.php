<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Frontend\ClientLeave;
use App\Models\StandardLeave;
use App\Http\Controllers\Controller;

class ClientLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leavs = StandardLeave::all();

        return view('frontend.payroll.leave.index',compact('leavs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Frontend\ClientLeave  $clientleave
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientLeave $clientleave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Frontend\ClientLeave  $clientleave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientLeave $clientleave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Frontend\ClientLeave  $clientleave
     * @return \Illuminate\Http\Response
     */
    public function delete(ClientLeave $clientleave)
    {
        //
    }
}
