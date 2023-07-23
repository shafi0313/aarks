<?php

namespace App\Http\Controllers;

use App\Models\Coefficient;
use Illuminate\Http\Request;
use App\Imports\CoefficientImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use App\Aarks\CoefficientImportCollection;

class CoefficientController extends Controller
{
    public function index()
    {
        $coefficient = Coefficient::all();
        return view('admin.coefficient.index', compact('coefficient'));
    }
    public function store(Request $request, CoefficientImportCollection $collection)
    {
        $request->validate([
            'holding_type'=>'required|string',
            'csvfile' => 'required|mimes:csv,txt|file'
        ]);
        //
        // Excel::import(new CoefficientImport($request->holding_type),$request->csvfile);
        try {
            Excel::import($collection, $request->csvfile);
            $collection->tempSolution($request->holding_type);
            Alert::success('Upload Coefficient Statement', 'Coefficient statement was successfully uploaded');
        } catch (\Exception  $exception) {
            Alert::error('Upload Coefficient Statement', $exception->getMessage());
        }

        return back();
    }

    public function edit(Coefficient $coefficient)
    {
        //
    }

    public function update(Request $request, Coefficient $coefficient)
    {
        //
    }

    public function destroy(Coefficient $coefficient)
    {
        //
    }
}
