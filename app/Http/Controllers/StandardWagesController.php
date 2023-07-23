<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StandardWages;
use App\Http\Controllers\Controller;

class StandardWagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wages = StandardWages::paginate(200);
        return view('admin.wages.index', compact('wages'));
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
            'name'         => 'required|string',
            'type'         => 'required|string',
            'link_group'   => 'required|integer',
            'regular_rate' => 'sometimes',
            'hourly_rate'  => 'sometimes',
        ]);
        try {
            StandardWages::create($data);
            toast('Wages Crated!', 'success');
        } catch (\Throwable $th) {
            toast($th->getMessage(), 'error');
        }
        return back();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StandardWages  $standardWages
     * @return \Illuminate\Http\Response
     */
    public function edit(StandardWages $wages)
    {
        return view('admin.wages.edit', compact('wages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StandardWages  $standardWages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StandardWages $wages)
    {
        $data = $this->validate($request, [
            'name'         => 'required|string',
            'type'         => 'required|string',
            'link_group'   => 'required|string',
            'regular_rate' => 'sometimes',
            'hourly_rate'  => 'sometimes',
        ]);

        if ($request->has('regular_rate')) {
            $data['regular_rate'] = $request->get('regular_rate');
            $data['hourly_rate'] = 0;
        }
        if ($request->has('hourly_rate')) {
            $data['hourly_rate'] = $request->get('hourly_rate');
            $data['regular_rate'] = 0;
        }

        try {
            $wages->update($data);
            toast('Wages Updated!', 'success');
        } catch (\Throwable $th) {
            toast($th->getMessage(), 'error');
        }
        return redirect()->route('stanwages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StandardWages  $standardWages
     * @return \Illuminate\Http\Response
     */
    public function delete(StandardWages $wages)
    {
        try {
            $wages->delete();
            toast('Wages Deleted!', 'warning');
        } catch (\Throwable $th) {
            toast($th->getMessage(), 'error');
        }
        return back();
    }
}
