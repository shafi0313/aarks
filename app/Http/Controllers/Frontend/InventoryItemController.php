<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Frontend\Measure;
use App\Models\ClientAccountCode;
use App\Http\Controllers\Controller;
use App\Models\Frontend\CustomerCard;
use App\Models\Frontend\InventoryItem;
use App\Http\Requests\InvItemStoreRequest;
use App\Models\Frontend\InventoryCategory;
use App\Models\Frontend\InventoryRegister;
use App\Http\Requests\InvItemUpdateRequest;

class InventoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.inventory.item.select_activity', compact('client'));
    }

    public function report(Client $client, Profession $profession)
    {
        $cats = InventoryCategory::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('parent_id', '!=', 0)
            ->orderBy('name')
            ->get();
        $measures = Measure::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('name')
            ->get();
        $customers = CustomerCard::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();
        $expences = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where(function ($q) {
                $q->where('code', 'like', '2%')
                ->orWhere(function ($r) {
                    $r->where('code', 'like', '5%')
                    ->where('type', 1);
                });
            })
            ->orderBy('code')
            ->get();
        $incomes = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where(function ($q) {
                $q->where('code', 'like', '1%')
                ->orWhere(function ($r) {
                    $r->where('code', 'like', '5%')
                    ->where('type', 1);
                });
            })
            ->orderBy('code')
            ->get();
        $assets = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', 'like', '552%')
            ->orderBy('code')
            ->get();

        return view('frontend.inventory.item.index', compact('client', 'profession', 'cats', 'measures', 'customers', 'expences', 'incomes', 'assets'));
    }
    
    public function measure(Request $request)
    {
        $data = $request->validate([
            'client_id'     => 'required',
            'profession_id' => 'required',
            'name'          => 'required',
            'details'       => 'required',
        ]);
        try {
            Measure::create($data);
            $measures = Measure::where('client_id', $request->client_id)
                ->where('profession_id', $request->profession_id)
                ->orderBy('name')
                ->get();
            $msg = ['status'=>200,'message'=>'Measure Created!','measures'=>$measures];
        } catch (\Exception $e) {
            #$e->getMessage();
            $msg = ['status'=>500,'message'=>'Measure Not Created!'];
        }
        return response()->json($msg);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvItemStoreRequest $request)
    {
        $data = $request->validated();
        foreach ($request->type as $i => $type) {
            $data['type']                   = $type;
            $data['measure_id']             = $request->measure_id[$i];
            $data['price']                  = $request->price[$i];
            $data['gst_code']               = $request->gst_code[$i];
            $data['customer_card_id']       = $request->customer_card_id[$i];
            $data['client_account_code_id'] = $request->client_account_code_id[$i];
            $item = InventoryItem::create($data);
            if ($request->has('qun_rate') && $request->has('qun_hand') && $item->type == 3) {
                $reg['client_id']         = $item->client_id;
                $reg['profession_id']     = $item->profession_id;
                $reg['inventory_item_id'] = $item->id;
                $reg['item_name']         = Str::slug($item->item_name).'-'.$item->item_number;
                $reg['source']            = 'stock';
                $reg['date']              = makeBackendCompatibleDate($request->qun_date);
                $reg['close_qty']         = $request->qun_hand;
                $reg['close_rate']        = $request->qun_rate;
                $reg['close_amount']      = $request->qun_hand * $request->qun_rate;
                InventoryRegister::create($reg);
            }
        }
        try {
            toast('Inventory Item store Success', 'success');
        } catch (\Exception $e) {
            toast('Inventory Item store Not Success', 'error');
            return $e->getMessage();
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Frontend\InventoryItem  $inv_item
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryItem $inv_item)
    {
        $inv_item->load(['client','profession']);

        // $client = Client::with('professions')->find(client()->id);
        // $profession = Profession::find(client()->profession_id);

        $measures = Measure::where('client_id', $inv_item->client->id)
            ->where('profession_id', $inv_item->profession->id)
            ->orderBy('name')
            ->get();
        $customers = CustomerCard::where('client_id', $inv_item->client->id)
            ->where('profession_id', $inv_item->profession->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $expences = ClientAccountCode::where('client_id', $inv_item->client->id)
            ->where('profession_id', $inv_item->profession->id)
            ->where(function ($q) {
                $q->where('code', 'like', '2%')
                ->orWhere(function ($r) {
                    $r->where('code', 'like', '5%')
                    ->where('type', 1);
                });
            })
            ->orderBy('code')
            ->get();
        $incomes = ClientAccountCode::where('client_id', $inv_item->client->id)
            ->where('profession_id', $inv_item->profession->id)
            ->where(function ($q) {
                $q->where('code', 'like', '1%')
                ->orWhere(function ($r) {
                    $r->where('code', 'like', '5%')
                    ->where('type', 1);
                });
            })
            ->orderBy('code')
            ->get();

        $assets = ClientAccountCode::where('client_id', $inv_item->client->id)
            ->where('profession_id', $inv_item->profession->id)
            ->where('code', 'like', '552%')
            ->orderBy('code')
            ->get();

        return view('frontend.inventory.item.list.edit', compact( 'inv_item', 'measures', 'customers', 'expences', 'incomes', 'assets'));
    }


    public function listItem()
    {
        $client = Client::find(client()->id);
        $items = InventoryItem::where('client_id', $client->id)->get();
        return view('frontend.inventory.item.list.index', compact('client', 'items'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Frontend\InventoryItem  $inv_item
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryItem $inv_item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Frontend\InventoryItem  $inv_item
     * @return \Illuminate\Http\Response
     */
    public function update(InvItemUpdateRequest $request, InventoryItem $inv_item)
    {
        $data = $request->validated();
        foreach ($request->type as $i => $type) {
            $data['type'] = $type;
            $data['measure_id'] = $request->measure_id[$i];
            $data['price'] = $request->price[$i];
            $data['gst_code'] = $request->gst_code[$i];
            $data['customer_card_id'] = $request->customer_card_id[$i];
            $data['client_account_code_id'] = $request->client_account_code_id[$i];
            if ($inv_item->type == $type) {
                $inv_item->update($data);
            } else {
                InventoryItem::create($data);
            }
        }
        try {
            toast('Inventory Item Update Success', 'success');
        } catch (\Exception $e) {
            toast('Inventory Item Update Not Success', 'error');
            #$e->getMessage();
        }
        return redirect()->route('inv_item.listItem');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Frontend\InventoryItem  $inv_item
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryItem $inv_item)
    {
        //
    }
}
