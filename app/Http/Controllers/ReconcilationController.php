<?php

namespace App\Http\Controllers;

use App\Models\Reconcilation;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReconcilationRequest;
use App\Http\Requests\UpdateReconcilationRequest;

class ReconcilationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreReconcilationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReconcilationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reconcilation  $reconcilation
     * @return \Illuminate\Http\Response
     */
    public function show(Reconcilation $reconcilation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reconcilation  $reconcilation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reconcilation $reconcilation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateReconcilationRequest  $request
     * @param  \App\Reconcilation  $reconcilation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReconcilationRequest $request, Reconcilation $reconcilation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reconcilation  $reconcilation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reconcilation $reconcilation)
    {
        //
    }
}
