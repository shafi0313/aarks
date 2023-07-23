<?php

namespace App\Http\Controllers\Frontend;


use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\StandardWages;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Frontend\ClientWages;
use RealRashid\SweetAlert\Facades\Alert;

class ClientWagesController extends Controller
{
    // protected $client = client()->id;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wages =   ClientWages::where('client_id',client()->id)->get();
        $stanwages = StandardWages::all();
        return view('frontend.payroll.manage.manage_wages',compact('wages','stanwages',));

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
            'name'=>'required|string',
            'type'=>'required|string',
            'link_group'   => 'required|integer',
            'regular_rate'=>'sometimes',
            'hourly_rate'=>'sometimes',
            'client_id' => 'required'
        ]);

        $data['action']=1;
        try {
            ClientWages::create($data);
            toast('Wages Created Successful','success');
        } catch (\Throwable $th) {
            toast($th->getMessage(),'error');
        }
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Frontend\ClientWages  $clientWages
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientWages $clientWages)
    {
        return view('frontend.payroll.manage.update_wages',compact('clientWages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Frontend\ClientWages  $clientWages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientWages $clientWages)
    {
        $data = $this->validate($request, [
            'name'=>'required|string',
            'type'=>'required|string',
            'link_group'   => 'required|integer',
            'regular_rate'=>'sometimes',
            'hourly_rate'=>'sometimes',
            'client_id' => 'required'
        ]);
        if($request->has('regular_rate')){
            $data['regular_rate'] = $request->get('regular_rate');
            $data['hourly_rate'] = 0;
        }
        if($request->has('hourly_rate')){
            $data['hourly_rate'] = $request->get('hourly_rate');
            $data['regular_rate'] = 0;
        }
        try {
            $clientWages->update($data);
            toast('Wages Updated!', 'success');
        } catch (\Throwable $th) {
            toast($th->getMessage(), 'error');
        }
        return redirect()->route('wages.index');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Frontend\ClientWages  $clientWages
     * @return \Illuminate\Http\Response
     */
    public function delete(ClientWages $clientWages)
    {
        $clientWages->delete();
        toast('Wages Deleted!','warning');
        return back();
    }
}
