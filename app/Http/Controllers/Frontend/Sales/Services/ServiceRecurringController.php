<?php

namespace App\Http\Controllers\Frontend\Sales\Services;

use PDF;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\Frontend\Dedotr;
use App\Models\ClientAccountCode;
use App\Models\Frontend\Recurring;
use Illuminate\Support\Facades\DB;
use App\Mail\RecurringViewableMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Frontend\CustomerCard;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\DedotrQuoteRequest;
use App\Actions\RecurringGenerate\RecurringAutoGenerateAction;

class ServiceRecurringController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.sales.recurring.select_activity', compact('client'));
    }
    public function show($id)
    {
        $client    = client();
        $profession = Profession::findOrFail($id);
        $customers = CustomerCard::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('type', 1)
            ->orderBy('name')->get();
        $codes     = ClientAccountCode::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('code', 'like', '1%')
            ->where('type', '2')
            ->orderBy('code')
            ->get();

        $liquid_codes = ClientAccountCode::where('additional_category_id', aarks('liquid_asset_id'))
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('code')
            ->get();

        return view('frontend.sales.recurring.recurring', compact('client', 'customers', 'codes', 'profession', 'liquid_codes'));
    }

    public function getCustomerInfo(Request $request)
    {    
        if ($request->ajax()) {
            $customer = CustomerCard::find($request->customer_card_id);
            return response()->json(['customer' => $customer, 'status' => 200]);
        }
    }

    public function store(DedotrQuoteRequest $request)
    {
        $data          = $request->validated();
        $data['tran_date']   = $data['updated_at'] = $tran_date = makeBackendCompatibleDate($request->start_date);
        $data['recurring']   = $request->recurring ?? 1;
        $data['untill_date'] = $request->untill_date ? makeBackendCompatibleDate($request->untill_date) : null;
        $data['unlimited']   = $request->unlimited ?? 0;
        $data['recur_tran']  = $request->recur_tran ?? 0;
        $data['mail_to']     = $request->mail_to ?? '';
        if (periodLock($request->client_id, $tran_date)) {
            return response()->json('Your enter data period is locked, check administration', 500);
        }
        $period = Period::where('client_id', $request->client_id)
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
                $data['price']          = $request->price[$i];
                $data['amount']         = $request->totalamount[$i];
                $data['job_title']      = $request->job_title[$i];
                $data['job_des']        = $request->job_des[$i];

                if ($request->is_tax[$i] == 'yes') {
                    $data['tax_rate']   = 10;
                } else {
                    $data['tax_rate']   = 0;
                }
                Recurring::create($data);
                $toast = 'Recurring Service Create successfully';
                $message = ['status' => 200, 'message' => $toast, 'inv_no' => Recurring::whereClientId($request->client_id)->whereProfessionId($request->profession_id)->max('inv_no') + 1];
            }
        } else {
            $toast = ' please check your accounting period from the Accounts>add/edit period';
            $message = ['status' => 500, 'message' => $toast];
        }
        try {
            DB::commit();
            if ($request->recurr_type == 'E-mail & Save' && !$request->ajax()) {
                $customer = CustomerCard::find($request->customer_card_id);
                $client   = Client::find(client()->id);

                Mail::to('info@aarks.net.au')->send(new RecurringViewableMail($client, $customer, $request->inv_no));

                // Mail::to($customer->email)->send(new RecurringViewableMail($client, $customer, $request->inv_no));
                toast($toast, 'success');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $toast = $e->getMessage();
            $message = ['status' => 500, 'message' => $toast];
        }
        return response()->json($message);
    }


    public function manage()
    {
        $client     = client();
        $recurrings = Recurring::where('client_id', $client->id)
            ->where('chart_id', 'not like', '551%')
            ->where('job_title', '!=', '')
            ->orderBy('tran_date','DESC')
            ->get();
        return view('frontend.sales.recurring.manage', compact('client', 'recurrings'));
    }

    public function edit(Request $request, $inv_no)
    {
        $invoices    = Recurring::with(['client', 'customer', 'item'])
            ->where('client_id', client()->id)
            ->where('job_title', '!=', '')
            ->where('inv_no', $inv_no)->get();
        $invoice        = $invoices->first();
        $client     = Client::find($invoice->client_id);
        $profession = Profession::find($invoice->profession_id);
        if (periodLock($client->id, $invoice->tran_date)) {
            Alert::error('Your enter data period is locked, check administration');
            return back();
        }
        if ($request->ajax()) {
            return response()->json(['invoices' => $invoices, 'status' => 200]);
        }
        if ($invoices->count() > 0) {
            $codes   = ClientAccountCode::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where(function ($q) {
                    $q->where('code', 'like', '1%')
                        ->orWhere('code', 'like', '2%')
                        ->orWhere('code', 'like', '5%')
                        ->orWhere('code', 'like', '9%');
                })
                ->where('type', '2')
                ->orderBy('code')
                ->get();
            $client     = Client::find($client->id);
            $customers  = CustomerCard::where('client_id', $client->id)
                ->where('profession_id', $profession->id)
                ->where('type', 1)->get();

            return view('frontend.sales.recurring.edit', compact('invoice', 'invoices', 'customers', 'client', 'codes', 'profession'));
        } else {
            toast('No Data found', 'error');
            return redirect()->route('invoice.manage');
        }
    }

    public function update(DedotrQuoteRequest $request, $inv_no)
    {
        // return $request;

        $data                 = $request->validated();
        $data['tran_date']   = $data['updated_at'] = $tran_date = makeBackendCompatibleDate($request->start_date);
        $data['recurring']   = $request->recurring ?? 1;
        $data['untill_date'] = $request->untill_date ? makeBackendCompatibleDate($request->untill_date) : null;
        $data['unlimited']   = $request->unlimited ?? 0;
        $data['recur_tran']  = $request->recur_tran ?? 0;
        $data['mail_to']     = $request->mail_to ?? '';
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
            foreach ($request->job_title as $i => $job_title) {
                $recur = Recurring::where('id', $request->inv_id[$i])->first();
                $rprice = $request->price[$i];
                if ($request->has('disc_rate')) {
                    $data['amount'] = $price = $rprice - ($rprice * ($request->disc_rate[$i] / 100));
                    $data['disc_amount'] = ($rprice * ($request->disc_rate[$i] / 100));
                }
                if ($request->has('freight_charge')) {
                    $data['amount'] = $price = $price + $request->freight_charge[$i];
                }
                if ($request->is_tax[$i] == 'yes') {
                    $data['amount'] =  $price + ($price * 0.1);
                    $data['tax_rate']   = 10;
                } else {
                    $data['amount'] =  $rprice;
                    $data['tax_rate']   = 0;
                }

                $data['job_title']      = $job_title;
                $data['job_des']        = $request->job_des[$i];
                $data['price']          = $rprice;
                $data['disc_rate']      = $request->disc_rate[$i];
                $data['freight_charge'] = $request->freight_charge[$i];
                $data['chart_id']       = $request->chart_id[$i];
                $data['is_tax']         = $request->is_tax[$i];

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
            $message = ['status' => 500, 'message' => $toast];
        }
        try {
            DB::commit();
            $toast = 'Recurring Services Updated successfully';
            $message = ['status' => 200, 'message' => $toast, 'inv_no' => Recurring::whereClientId($request->client_id)->whereProfessionId($request->profession_id)->max('inv_no') + 1];
        } catch (\Exception $e) {
            DB::rollBack();
            $toast = $e->getMessage();
            $message = ['status' => 500, 'message' => $toast];
        }
        toast($toast, 'success');
        return redirect()->route('recurring.manage');
    }

    public function destroy($inv_no)
    {
        try {
            $inv = Recurring::where('inv_no', $inv_no)->where('client_id', client()->id)->first();
            if (periodLock($inv->client_id, $inv->tran_date)) {
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
    public static function recurring()
    {
        // return Carbon::parse('tomorrow 15:00')->format('h m s A');
        return RecurringAutoGenerateAction::recurring();
    }



    // ALl Invoice Report Email View
    public function emailViewRecurring($source, $inv_no, $client)
    {
        $client   = Client::findOrFail(open_decrypt($client));
        $inv_no   = open_decrypt($inv_no);
        $invoices = Recurring::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        // if ($source == 'item') {
        //     return view('frontend.sales.mail-view.invItem', compact('client', 'source', 'invoices', 'inv_no'));
        // }
        // return $invoices;
        return view('frontend.sales.recurring.print', compact('client', 'source', 'invoices', 'inv_no'));
    }

    public function print($source, $inv_no, Client $client, $type = null)
    {
        $client   = Client::find(client()->id);
        $invoices = Dedotr::with(['client', 'customer'])->where('client_id', $client->id)
            ->where('inv_no', $inv_no)->get();
        $inv = $invoices->first();
        // if (periodLock($client->id, $inv->tran_date)) {
        //     Alert::error('Your enter data period is locked, check administration');
        //     return back();
        // }
        if ($source == 'item') {
            // return view('frontend.sales.recurring.pdf', compact('client', 'source', 'invoices'));
            $pdf = PDF::loadView('frontend.sales.recurring.pdf', compact('client', 'source', 'invoices'));
        } else {
            $pdf = PDF::loadView('frontend.sales.recurring.pdf', compact('client', 'source', 'invoices'));
        }
        if ($type == 'download') {
            return $pdf->setPaper('a4', 'portrait')->setWarnings(false)->download(invoice($inv_no).'.pdf');
        }
        return $pdf->setPaper('a4', 'portrait')->setWarnings(false)->stream();
    }
}
