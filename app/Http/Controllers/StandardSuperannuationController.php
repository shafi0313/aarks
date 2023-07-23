<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StandardSuperannuation;

class StandardSuperannuationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sups = StandardSuperannuation::all();
        return view('admin.superannuation.index', compact('sups'));
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
            "t_excl_amt" => 'sometimes'
        ]);

        try {
            StandardSuperannuation::create($data);
            toast('Superannuation Created!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StandardSuperannuation  $superannuation
     * @return \Illuminate\Http\Response
     */
    public function edit(StandardSuperannuation $superannuation)
    {
        return view('admin.superannuation.update', compact('superannuation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StandardSuperannuation  $superannuation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StandardSuperannuation $superannuation)
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
            "t_excl_amt" => 'sometimes'
        ]);

        try {
            $superannuation->update($data);
            toast('Superannuation Updated!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return redirect()->route('superannuation.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StandardSuperannuation  $superannuation
     * @return \Illuminate\Http\Response
     */
    public function delete(StandardSuperannuation $superannuation)
    {
        try {
            $superannuation->delete();
            toast('Superannuation Deleted!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
}
