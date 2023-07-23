<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Frontend\BsbTable;
use Illuminate\Routing\Controller;

class BsbTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $bsbs = BsbTable::where('client_id', client()->id)->get();
            return response()->json(['bsbs'=>$bsbs,'status'=>200]);
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
    public function store(Request $request)
    {
        $data = $request->validate([
            "profession_id"  => "required",
            "bsb_number"    => "required",
            "account_number"=> "required",
        ]);
        $data['client_id'] = client()->id;
        try {
            BsbTable::create($data);
            toast('BSB Created!', 'success');
        } catch (\Exception $e) {
            toast('Server Side Error', 'error');
            // toast($e->getMessage(), 'error');
            // return $e;
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Frontend\BsbTable  $bsbtable
     * @return \Illuminate\Http\Response
     */
    public function show(BsbTable $bsbtable)
    {
        try {
            $bsbtable->delete();
            toast('BSB Deleted!', 'success');
        } catch (\Exception $e) {
            toast('Server Side Error', 'error');
            // toast($e->getMessage(), 'error');
            // return $e;
        }
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Frontend\BsbTable  $bsbtable
     * @return \Illuminate\Http\Response
     */
    public function edit(BsbTable $bsbtable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Frontend\BsbTable  $bsbtable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BsbTable $bsbtable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Frontend\BsbTable  $bsbtable
     * @return \Illuminate\Http\Response
     */
    public function destroy(BsbTable $bsbtable)
    {
        //
    }
}
