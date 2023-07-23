<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Profession;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Frontend\CustomerCard;
use App\Http\Requests\AddCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

class PersonalCardController extends Controller
{
    public function index()
    {
        //
    }
    public function show(Profession $personal)
    {
        $profession =  $personal;
        return view('frontend.add_card.personal', compact('profession'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCustomerRequest $request)
    {
        $data             = $request->validated();
        if ($data['status']==1) {
            try {
                CustomerCard::create($data);
                toast('Customer Created!', 'success');
            } catch (\Exception $e) {
                toast($e->getMessage(), 'error');
            }
        } else {
            toast('Please Check Status!', 'warning');
        }
        return back();
    }
    public function view($profession)
    {
        $client = client()->id;
        $customers = CustomerCard::where('type', 3)->where('client_id', $client)->where('profession_id', $profession)->get();
        return view('frontend.card_list.supplier', compact('customers'));
    }
    public function edit(CustomerCard $personal)
    {
        return view('frontend.card_list.update_customer', compact('supplier'));
    }
    public function update(UpdateCustomerRequest $request, CustomerCard $personal)
    {
        $data             = $request->validated();

        if (!$request->has('bsb_table_id')) {
            $data['bsb_table_id'] = null;
        }

        if ($data['status']==1) {
            try {
                $personal->update($data);
                toast('Personal Updated!', 'success');
            } catch (\Exception $e) {
                toast($e->getMessage(), 'error');
            }
            return redirect()->route('personal.view', $personal->profession_id);
        } else {
            toast('Please Check Status!', 'warning');
            return back();
        }
    }
    public function delete(CustomerCard $personal)
    {
        try {
            $personal->delete();
            toast('Personal Deleted!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
}
