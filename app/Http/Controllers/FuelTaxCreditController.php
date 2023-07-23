<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Fuel_tax_credit;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Contracts\Permission;

class FuelTaxCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fuel_tax_credits = Fuel_tax_credit::all();
        return view('admin.fuel_tax_credit.index', compact('fuel_tax_credits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.fuel_tax_credit.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data['financial_year'] = $request->get('financial_year');
        $data['start_date']     = $request->get('start_date');
        $data['end_date']       = $request->get('end_date');
        $data['rate']           = $request->get('rate');

        try {
            Fuel_tax_credit::create($data);
            Alert::success('Data Insert', 'Fuel Tax Credit Successfully Inserted');
            return redirect()->route('FuelTaxCredit.index');
        } catch (\Exception $ex) {
            Alert::error('DataInsert', $ex->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fuel_tax_credits = Fuel_tax_credit::find($id);
        return view('admin.fuel_tax_credit.edit', compact('fuel_tax_credits'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data['financial_year'] = $request->get('financial_year');
        $data['start_date']     = $request->get('start_date');
        $data['end_date']       = $request->get('end_date');
        $data['rate']           = $request->get('rate');

        try {
            $update  = Fuel_tax_credit::find($id);
            $update->update($data);
            Alert::success('Data Updated', 'Fuel Tax Credit Successfully Updated');
            return redirect()->route('FuelTaxCredit.index');
        } catch (\Exception $ex) {
            Alert::error('DataInsert', $ex->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroy = Fuel_tax_credit::find($id);
        $destroy->delete();
        Alert::success('Fuel Tax Credit Deleted', 'Fuel Tax Credit deleted successfully')->autoClose(3000);
        return redirect()->route('FuelTaxCredit.index');
    }
}
