<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Frontend\InventoryCategory;

class InventoryCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.inventory.category.select_activity', compact('client'));
    }
    public function report(Client $client, Profession $profession)
    {
        $invs = InventoryCategory::with('subcat')
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('parent_id', 0)
            ->get();
        // return $invs->first()->subcat;
        return view('frontend.inventory.category.index', compact('client', 'profession', 'invs'));
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
            "client_id"    => 'required',
            "profession_id"=> 'required',
            "name"         => 'required',
        ]);
        try {
            InventoryCategory::create($data);
            toast('Inventory Category Created!', 'success');
        } catch (\Exception $e) {
            toast('Inventory Category Not Created!', 'error');
            #$e->getMessage();
        }
        return redirect()->back();
    }
    public function addSub(Request $request)
    {
        $data = $request->validate([
            "client_id"     => 'required',
            "profession_id" => 'required',
            "name"          => 'required',
            "parent_id"     => 'required',
        ]);
        try {
            InventoryCategory::create($data);
            toast('Inventory Sub Category Created!', 'success');
        } catch (\Exception $e) {
            toast('Inventory Sub Category Not Created!', 'error');
            #$e->getMessage();
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Frontend\InventoryCategory  $inv_category
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryCategory $inv_category)
    {
        try {
            $inv_category->delete();
            InventoryCategory::where('parent_id', $inv_category->id)->delete();
            toast('Deleted', 'success');
        } catch (\Exception $e) {
            #$e->getMessage();
            toast('Not Deleted', 'success');
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Frontend\InventoryCategory  $inv_category
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryCategory $inv_category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Frontend\InventoryCategory  $inv_category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryCategory $inv_category)
    {
        $request->validate([
            'name' => 'required'
        ]);
        try {
            $inv_category->update($request->only('name'));
            toast('Updated', 'success');
        } catch (\Exception $e) {
            #$e->getMessage();
            toast('Not Updated', 'success');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Frontend\InventoryCategory  $inv_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryCategory $inv_category)
    {
    }
}
