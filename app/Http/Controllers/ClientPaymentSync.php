<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Http\Controllers\Controller;
use App\Models\Frontend\CustomerCard;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Frontend\DedotrPaymentReceive;

class ClientPaymentSync extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.payment_sync.index')) {
            return $error;
        }
        return view('admin.client_payment_sync.index');
    }
    public function search(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.payment_sync.index')) {
            return $error;
        }
        $tran_id =  $request->search;
        $ledgers = GeneralLedger::where('transaction_id', $tran_id)->get();
        if ($ledgers->count() == 0) {
            Alert::warning('No Data', 'There is no data matching with this transaction.');
            return redirect()->back();
        }
        return view('admin.client_payment_sync.list', compact(['tran_id', 'ledgers']));
    }
    public function destroy(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.payment_sync.delete')) {
            return $error;
        }
        try {
            GeneralLedger::where('transaction_id', $request->search)->forceDelete();
            Gsttbl::where('trn_id', $request->search)->forceDelete();
            Alert::success('Successfully', 'Transaction Deleted');
            return redirect()->route('payment_sync.index');
        } catch (\Exception $e) {
            //return $e->getMessage();
            Alert::error('Server Error');
            return redirect()->route('payment_sync.index');
        }
    }
    /*
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.payment_sync.index')) {
            return $error;
        }

        $clients = Client::all();
        return view('admin.client_payment_sync.client', compact('clients'));
    }
    public function profession(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.payment_sync.index')) {
            return $error;
        }
        $customers = CustomerCard::where('client_id', $client->id)->orderBy('name')->get(['id', 'name']);
        return view('admin.client_payment_sync.profession', compact('client', 'customers'));
    }
    public function list(Request $request, Client $client)
    {
        if ($error = $this->sendPermissionError('admin.payment_sync.index')) {
            return $error;
        }
        $profession = Profession::findOrFail($request->profession_id);
        $customer   = CustomerCard::findOrFail($request->customer_id);
        $payments   = DedotrPaymentReceive::whereClientId($client->id)
            ->whereProfessionId($profession->id)
            ->whereCustomerCardId($customer->id)
            ->pluck('tran_id');
        $ledgers    = GeneralLedger::whereClientId($client->id)
            ->whereProfessionId($profession->id)
            ->whereNotIn('transaction_id', $payments)
            ->whereChartId(551100)
            ->whereSource('PIN')->get();
        return view('admin.client_payment_sync.list', compact('client', 'customer', 'profession', 'ledgers'));
    }
    public function post(Request $request, Client $client)
    {
        if ($error = $this->sendPermissionError('admin.payment_sync.index')) {
            return $error;
        }
        $profession = Profession::findOrFail($request->profession_id);
        $customer   = CustomerCard::findOrFail($request->customer_id);
        $ledgers    = GeneralLedger::whereClientId($client->id)
            ->whereProfessionId($profession->id)->whereChartId(551100)
            ->whereSource('PIN')->get();
        $pay_inv  = DedotrPaymentReceive::max('inv_no');
        $payments = DedotrPaymentReceive::whereClientId($client->id)
            ->whereProfessionId($profession->id)
            ->whereCustomerCardId($customer->id)
            ->get();
        foreach ($ledgers as $ledger) {
            $payment = $payments->where('tran_id', $ledger->transaction_id)->first();
            if (!$payment) {
                DedotrPaymentReceive::create([
                    'client_id'            => $client->id,
                    'profession_id'        => $profession->id,
                    'customer_card_id'     => $customer->id,
                    'chart_id'             => $ledger->chart_id,
                    'source'               => $client->invoiceLayout->layout == 2?2:1,
                    'tran_date'            => $ledger->date->format('Y-m-d'),
                    'tran_id'              => $ledger->transaction_id,
                    'inv_no'               => $pay_inv++,
                    'payment_amount'       => abs($ledger->balance),
                    'accum_payment_amount' => abs($ledger->balance),
                ]);
            }
        }
        Alert::success('Synchronise done.');
        return redirect()->back();
        // return view('admin.client_payment_sync.list', compact('client', 'customer', 'profession', 'ledgers', 'payments'));
    }
    */
}
