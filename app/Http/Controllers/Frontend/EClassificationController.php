<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Frontend\EClassification;


class EClassificationController extends Controller
{
    public function index()
    {
        $ECS = EClassification::where('client_id',client()->id)->get();
        return view('frontend.payroll.employment.classification',compact('ECS'));

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name'      => 'required|string',
            'status'    => 'required|string',
            'client_id' => 'required'
        ]);
        try {
            EClassification::create($data);
            toast('Classification Created Successful', 'success');
        } catch (\Throwable $th) {
            toast($th->getMessage(), 'error');
        }
        return back();

    }

    public function show(EClassification $classification)
    {
        //
    }

    public function edit(EClassification $classification)
    {
        return view('frontend.payroll.employment.update',compact('classification'));
    }

    public function update(Request $request, EClassification $classification)
    {
        $data = $this->validate($request, [
            'name'   => 'required|string',
            'status' => 'required|string',
        ]);
        try {
            $classification->update($data);
            toast('Classification Updated Successful', 'success');
        } catch (\Throwable $th) {
            toast($th->getMessage(), 'error');
        }
        return redirect()->route('classification.index');

    }

    public function delete(EClassification $classification)
    {
        $classification->delete();
        toast('Classification Deleted!','warning');
        return back();
    }
}
