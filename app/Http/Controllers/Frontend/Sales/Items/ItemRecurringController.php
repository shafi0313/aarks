<?php

namespace App\Http\Controllers\Frontend\Sales\Items;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\ClientAccountCode;
use App\Models\Frontend\Recurring;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Frontend\CustomerCard;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\DedotrQuoteRequest;
use App\Models\Frontend\InventoryCategory;
use App\Actions\RecurringGenerate\RecurringAutoGenerateAction;

class ItemRecurringController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.sales.recurring_item.select_activity', compact('client'));
    }

    public function create()
    {
        //
    }
    public function manage()
    {
        $client     = client();
        $recurrings = Recurring::where('client_id', $client->id)
                    ->where('chart_id', 'not like', '551%')
                    ->where('item_name', '!=', '')
                    ->get();
        return view('frontend.sales.recurring_item.manage', compact('client', 'recurrings'));
    }

    public function store(DedotrQuoteRequest $request)
    {
        // return $request;
        $data                 = $request->validated();
        $data ['tran_date']   = $tran_date = makeBackendCompatibleDate($request->start_date);
        $data ['recurring']   = $request->recurring??1;
        $data ['untill_date'] = $request->untill_date?makeBackendCompatibleDate($request->untill_date):null;
        $data ['unlimited']   = $request->unlimited??0;
        $data ['recur_tran']  = $request->recur_tran??0;
        $data ['mail_to']     = $request->mail_to??'';
        if (periodLock($request->client_id, $tran_date)) {
            return response()->json('Your enter data period is locked, check administration', 500);
        }
        $period         = Period::where('client_id', $request->client_id)
                        ->where('profession_id', $request->profession_id)
                        // ->where('start_date', '<=', $tran_date->format('Y-m-d'))
                        ->where('end_date', '>=', $tran_date->format('Y-m-d'))
                        ->first();
        DB::beginTransaction();
        if ($period != '') {
            // $tran_id = $request->client_id.$request->profession_id.$period->id.$tran_date->format('dmy').rand(11, 99);
            $tran_id = transaction_id('RIV');

            foreach ($request->chart_id as $i => $chart_id) {
                $data['chart_id']       = $chart_id;
                $data['tran_id']        = $tran_id;
                $data['disc_rate']      = $request->disc_rate[$i];
                $data['disc_amount']    = $request->disc_amount[$i];
                $data['freight_charge'] = $request->freight_charge[$i];
                $data['is_tax']         = $request->is_tax[$i];
                $data['price']          = $request->amount[$i];
                $data['amount']         = $request->totalamount[$i];
                $data['ex_rate']        = $request->rate[$i];
                $data['item_no']        = $request->item_id[$i];
                $data['item_name']      = $request->item_name[$i];
                $data['item_quantity']  = $request->quantity[$i];

                if ($request->is_tax[$i] == 'yes') {
                    $data ['tax_rate']   = 10;
                } else {
                    $data ['tax_rate']   = 0;
                }

                Recurring::create($data);
            }
        } else {
            $toast = ' please check your accounting period from the Accouts>add/editperiod';
            $message = ['status'=>500,'message'=>$toast];
        }
        try {
            DB::commit();
            $toast='Recurring Item Create successfully';
            $message = ['status'=>200,'message'=>$toast,'inv_no'=> Recurring::whereClientId($request->client_id)->whereProfessionId($request->profession_id)->max('inv_no')+1];
        } catch (\Exception $e) {
            DB::rollBack();
            $toast=$e->getMessage();
            $message = ['status'=>500,'message'=>$toast];
        }
        return response()->json($message);
    }

    public function show($id)
    {
        $client    = client();
        $profession = Profession::findOrFail($id);
        $categories = InventoryCategory::with(['items'=>function ($q) {
            $q->where('type', '!=', 1);
        },'items.code'])->where('client_id', $client->id)
        ->where('profession_id', $profession->id)
        ->where('parent_id', '!=', 0)
        ->get();
        $customers = CustomerCard::where('client_id', $client->id)->where('profession_id', $profession->id)->where('type', 1)->orderBy('name')->get();
        $codes     = ClientAccountCode::where('client_id', $client->id)
        ->where('profession_id', $profession->id)
        ->where('code', 'like', '1%')
        ->where('type', '2')
        ->orderBy('code')
        ->get();

        $liquid_codes = ClientAccountCode::where('additional_category_id', aarks('liquid_asset_id'))
        ->where('client_id', $client->id)
        ->where('profession_id', $profession->id)
        ->orderBy('code', 'asc')
        ->get();

        return view('frontend.sales.recurring_item.recurring', compact('client', 'customers', 'codes', 'profession', 'liquid_codes', 'categories'));
    }

    public function edit(Request $request, $inv_no)
    {
        $invoices    = Recurring::with(['client', 'customer', 'item'])
                    ->where('item_name', '!=', '')
                    ->where('inv_no', $inv_no)->get();
        $invoice = $invoices->first();
        if (periodLock($invoice->client_id, $invoice->tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        if ($request->ajax()) {
            return response()->json(['invoices'=>$invoices,'status'=>200]);
        }
        if ($invoices->count() > 0) {
            $invoice = $invoices->first();
            $codes   = ClientAccountCode::where('client_id', $invoice->client_id)
                    ->where('profession_id', $invoice->profession_id)
                    ->where('code', 'like', '1%')
                    ->where('type', '2')
                    ->orderBy('code')
                    ->get();
            $client     = Client::find($invoice->client_id);
            $customers  = CustomerCard::where('client_id', $client->id)
                        ->where('profession_id', $invoice->profession_id)
                        ->where('type', 1)->get();
            $categories = InventoryCategory::with(['items'=>function ($q) {
                $q->where('type', '!=', 1);
            },'items.code'])->where('client_id', $client->id)
            ->where('profession_id', $invoice->profession_id)
            ->where('parent_id', '!=', 0)
            ->get();

            return view('frontend.sales.recurring_item.edit', compact('invoice', 'invoices', 'customers', 'client', 'categories', 'codes'));
        } else {
            toast('No Data found', 'error');
            return redirect()->route('invoice.manage');
        }
    }

    public function update(DedotrQuoteRequest $request, $inv_no)
    {
        $data                 = $request->validated();
        $data ['tran_date']   = $tran_date = makeBackendCompatibleDate($request->start_date);
        $data ['recurring']   = $request->recurring??1;
        $data ['untill_date'] = $request->untill_date?makeBackendCompatibleDate($request->untill_date):null;
        $data ['unlimited']   = $request->unlimited??0;
        $data ['recur_tran']  = $request->recur_tran??0;
        $data ['mail_to']     = $request->mail_to??'';
        if (periodLock($request->client_id, $tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        $period         = Period::where('client_id', $request->client_id)
                        ->where('profession_id', $request->profession_id)
                        // ->where('start_date', '<=', $tran_date->format('Y-m-d'))
                        ->where('end_date', '>=', $tran_date->format('Y-m-d'))
                        ->first();
        DB::beginTransaction();
        if ($period != '') {
            foreach ($request->chart_id as $i => $chart_id) {
                $recur = Recurring::where('id', $request->inv_id[$i])->first();
                $rprice = $request->amount[$i];
                if ($request->has('disc_rate')) {
                    $data['amount'] = $price = $rprice - ($rprice * ($request->disc_rate[$i]/100));
                    $data['disc_amount'] = ($rprice * ($request->disc_rate[$i]/100));
                }
                if ($request->has('freight_charge')) {
                    $data['amount'] = $price = $price + $request->freight_charge[$i];
                }
                if ($request->is_tax[$i] == 'yes') {
                    $data['amount'] =  $price + ($price * 0.1);
                } else {
                    $data['amount'] =  $rprice;
                }

                $data['chart_id']       = $chart_id;
                $data['disc_rate']      = $request->disc_rate[$i];
                $data['disc_amount']    = $request->disc_amount[$i];
                $data['freight_charge'] = $request->freight_charge[$i];
                $data['is_tax']         = $request->is_tax[$i];
                $data['price']          = $request->amount[$i];
                $data['ex_rate']        = $request->rate[$i];
                $data['item_no']        = $request->item_id[$i];
                $data['item_name']      = $request->item_name[$i];
                $data['item_quantity']  = $request->quantity[$i];

                if ($request->is_tax[$i] == 'yes') {
                    $data ['tax_rate']   = 10;
                } else {
                    $data ['tax_rate']   = 0;
                }

                if ($recur != '') {
                    $recur->update($data);
                } else {
                    $data['disc_amount'] = $request->disc_amount[$i];
                    $data['gst_amt']     = $request->gst_amt[$i];
                    $data['totalamount'] = $request->totalamount[$i];
                    $data['inv_id']      = $request->inv_id[$i];
                    Recurring::create($data);
                }
            }
        } else {
            $toast = ' please check your accounting period from the Accouts>add/editperiod';
            $message = ['status'=>500,'message'=>$toast];
        }
        try {
            DB::commit();
            $toast='Recurring Item Create successfully';
            $message = ['status'=>200,'message'=>$toast,'inv_no'=> Recurring::whereClientId($request->client_id)->whereProfessionId($request->profession_id)->max('inv_no')+1];
        } catch (\Exception $e) {
            DB::rollBack();
            $toast=$e->getMessage();
            $message = ['status'=>500,'message'=>$toast];
        }
        return redirect()->route('recurring_item.manage');
    }

    public function destroy($inv_no)
    {
        try {
            $invoice = Recurring::where('inv_no', $inv_no)->where('client_id', client()->id)->first();
            if (periodLock($invoice->client_id, $invoice->tran_date)) {
                Alert::error('Your enter data period is locked, check administration');
                return back();
            }
            Recurring::where('inv_no', $inv_no)->where('client_id', client()->id)->delete();
            toast('recurring deleted success', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
    public function delete(Request $req)
    {
        try {
            $inv = Recurring::find($req->id);
            if (periodLock($inv->client_id, $inv->tran_date)) {
                return response()->json('Your enter data period is locked, check administration', 500);
            }
            $inv->delete();
            $msg = 'recurring deleted success';
            $st = 200;
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $st = 500;
        }
        return response()->json(['message'=>$msg,'status'=>$st]);
    }
    public static function recurring()
    {
        // return Carbon::parse('tomorrow 24:00')->format('h m s A');
        return RecurringAutoGenerateAction::recurring();
    }
}
