<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StandardDeducation;
use App\Http\Controllers\Controller;

class StandardDeducationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dedus = StandardDeducation::all();
        return view('admin.deducation.index', compact('dedus'));
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
        ]);

        try {
            StandardDeducation::create($data);
            toast('Deduction Created!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StandardDeducation  $deducation
     * @return \Illuminate\Http\Response
     */
    public function edit(StandardDeducation $deducation)
    {
        return view('admin.deducation.update', compact('deducation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StandardDeducation  $deducation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StandardDeducation $deducation)
    {
        $data = $this->validate($request, [
            "name"    => 'required|string',
            "rate"    => 'sometimes',
            "tools"   => 'sometimes',
            "fix_amt" => 'sometimes',
            "period"  => 'sometimes',
            "limit"   => 'sometimes',
        ]);

        try {
            $deducation->update($data);
            toast('Deducation Updated!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return redirect()->route('deducation.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StandardDeducation  $deducation
     * @return \Illuminate\Http\Response
     */
    public function delete(StandardDeducation $deducation)
    {
        try {
            $deducation->delete();
            toast('Deducation Deleted!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
}
