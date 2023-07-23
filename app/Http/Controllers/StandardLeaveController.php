<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StandardLeave;
use App\Http\Controllers\Controller;

class StandardLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leavs = StandardLeave::all();
        return view('admin.leave.index', compact('leavs'));
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
            "carry"   => 'sometimes',
        ]);

        try {
            StandardLeave::create($data);
            toast('Leave Created!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StandardLeave  $standardleave
     * @return \Illuminate\Http\Response
     */
    public function edit(StandardLeave $standardleave)
    {
        return view('admin.leave.update', compact('standardleave'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StandardLeave  $standardleave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StandardLeave $standardleave)
    {
        $data = $this->validate($request, [
            "name"    => 'required|string',
            "rate"    => 'sometimes',
            "tools"   => 'sometimes',
            "fix_amt" => 'sometimes',
            "period"  => 'sometimes',
            "carry"   => 'sometimes',
        ]);

        try {
            $standardleave->update($data);
            toast('Leave Updated!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return redirect()->route('standardleave.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StandardLeave  $standardleave
     * @return \Illuminate\Http\Response
     */
    public function delete(StandardLeave $standardleave)
    {
        try {
            $standardleave->delete();
            toast('Leave Deleted!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
}
