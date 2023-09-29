<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use App\Models\Frontend\BsbTable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Frontend\CustomerCard;
use App\Http\Requests\AddCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

class CustomerCardController extends Controller
{
    public function show(Profession $customer)
    {
        $profession =  $customer;
        $creditCodes = ClientAccountCode::where(function ($q) {
            $q->where('code', 'like', '954%')->orWhere('code', 'like', '99%');
        })
            ->where('client_id', client()->id)
            ->where('profession_id', $profession->id)
            ->orderBy('code')
            ->get();
        $chk_card = CustomerCard::whereClientId(client()->id)->whereProfessionId($profession->id)->whereCustomerType('default')->first();
        return view('frontend.add_card.customer', compact('profession', 'creditCodes', 'chk_card'));
    }

    public function store(AddCustomerRequest $request)
    {
        $data                      = $request->validated();
        $data['by_date']           = $request->by_date ? makeBackendCompatibleDate($request->by_date) : null;
        $data['after_date']        = $request->after_date ? makeBackendCompatibleDate($request->after_date) : null;
        $data['opening_blnc_date'] = $request->opening_blnc_date ? makeBackendCompatibleDate($request->opening_blnc_date) : null;

        if ($data['status'] == 1 && $request->opening_blnc != '') {
            DB::beginTransaction();
            $code      = ClientAccountCode::find($request->credit_account);
            $customer  = CustomerCard::create($data);
            $tran_date = makeBackendCompatibleDate($request->opening_blnc_date);
            $ledger    = $credit = [
                'chart_id'               => 552100,
                'date'                   => $data['opening_blnc_date'] = $tran_date->format('Y-m-d'),
                'narration'              => 'Customer Opening balance',
                'source'                 => 'OPN',
                'debit'                  => 0,
                'credit'                 => 0,
                'gst'                    => 0,
                'balance'                => $request->opening_blnc,
                'balance_type'           => 1,
                'transaction_for'        => 'OPN',
                'client_id'              => $request->client_id,
                'profession_id'          => $request->profession_id,
                'client_account_code_id' => ClientAccountCode::where('code', 552100)
                    ->where('client_id', $request->client_id)
                    ->where('profession_id', $request->profession_id)
                    ->first()->id,
                'transaction_id'         => transaction_id('OPN_'.$customer->id),
            ];
            $credit['chart_id']               = $code->code;
            $credit['client_account_code_id'] = $code->id;
            $credit['balance_type']           = 2;
            if ($request->opening_blnc < 1) {
                $ledger['debit']        = 0;
                $ledger['credit']       = abs($request->opening_blnc);
                // Credit
                $credit['credit']       = 0;
                $credit['debit']        = abs($request->opening_blnc);
            } else {
                $ledger['credit']       = 0;
                $ledger['debit']        = $request->opening_blnc;
                // Credit
                $credit['debit']        = 0;
                $credit['credit']       = $request->opening_blnc;
            }

            GeneralLedger::create($ledger);
            GeneralLedger::create($credit);
            try {
                toast('Customer Created!', 'success');
                DB::commit();
            } catch (\Exception $e) {
                toast('Server Side Error', 'error');
                // toast($e->getMessage(), 'error');
                DB::rollBack();
            }
        } else {
            toast('Please Check Status!', 'warning');
        }
        return back();
    }

    public function view($profession)
    {
        $client = client()->id;
        $customers = CustomerCard::where('type', 1)
            ->where('client_id', $client)
            ->where('profession_id', $profession)->orderBy('name')
            ->get(['id','customer_type','customer_ref','name','type','phone','email','abn']);
        return view('frontend.card_list.customer', compact('customers'));
    }

    public function edit(CustomerCard $customer)
    {
        $creditCodes = ClientAccountCode::where(function ($q) {
            $q->where('code', 'like', '954%')->orWhere('code', 'like', '99%');
        })
            ->where('client_id', client()->id)
            ->where('profession_id', $customer->profession_id)
            ->orderBy('code')
            ->get();
        $bsbs = BsbTable::where('client_id', client()->id)->get();
        return view('frontend.card_list.update_customer', compact('customer', 'creditCodes', 'bsbs'));
    }

    // Update
    public function update(UpdateCustomerRequest $request, CustomerCard $customer)
    {
        $data = $request->validated();
        if (!$request->has('bsb_table_id')) {
            $data['bsb_table_id'] = null;
        }

        if ($data['status'] == 1 && $request->opening_blnc != '' && $request->credit_account != '' && $request->opening_blnc_date != '') {
            DB::beginTransaction();
            $code      = ClientAccountCode::find($request->credit_account);
            $tran_date = makeBackendCompatibleDate($request->opening_blnc_date);
            $tran_id   = transaction_id('OPN_'.$customer->id);
            $ledger    = $credit = [
                'chart_id'               => 552100,
                'date'                   => $data['opening_blnc_date'] = $tran_date->format('Y-m-d'),
                'narration'              => 'Customer Opening balance UP',
                'source'                 => 'OPN',
                'debit'                  => 0,
                'credit'                 => 0,
                'gst'                    => 0,
                'balance'                => $request->opening_blnc,
                'balance_type'           => 1,
                'transaction_for'        => 'OPN',
                'client_id'              => $customer->client_id,
                'profession_id'          => $customer->profession_id,
                'client_account_code_id' => ClientAccountCode::where('code', 552100)
                    ->where('client_id', $customer->client_id)
                    ->where('profession_id', $customer->profession_id)
                    ->first()->id,
                'transaction_id'         => $tran_id,
            ];
            $credit['chart_id']               = $code->code;
            $credit['client_account_code_id'] = $code->id;
            $credit['balance_type']           = 2;
            if ($request->opening_blnc < 1) {
                $ledger['debit']        = 0;
                $ledger['credit']       = abs($request->opening_blnc);
                // Credit
                $credit['credit']       = 0;
                $credit['debit']        = abs($request->opening_blnc);
            } else {
                $ledger['credit']       = 0;
                $ledger['debit']        = $request->opening_blnc;
                // Credit
                $credit['debit']        = 0;
                $credit['credit']       = $request->opening_blnc;
            }

            // Delete previous data by transaction_id
            $retrieveCustomerIds = GeneralLedger::where('source', 'OPN')->get(['transaction_id']);
            foreach ($retrieveCustomerIds as $retrieveCustomerId) {
                $customerId = explode('_', $retrieveCustomerId->transaction_id);
                if(!empty($customerId[1]) && $customerId[1] == $customer->id){
                    $currentTranId = $retrieveCustomerId->transaction_id;
                    GeneralLedger::where('source', 'OPN')->where('transaction_id', $currentTranId)->forceDelete();
                    // break;
                }
            }
            // Create new data
            GeneralLedger::create($credit);
            GeneralLedger::create($ledger);


            // $checkLedger = GeneralLedger::where('source', 'OPN')
            //     ->where('chart_id', 552100)
            //     ->first();
            // if ($checkLedger) {
            //     $checkLedger->update($ledger);
            // } else {
            //     GeneralLedger::create($ledger);
            // }

            // $checkCredit = GeneralLedger::where('source', 'OPN')
            //     ->where('transaction_id', $tran_id)
            //     ->where('chart_id', '!=', 552100)
            //     // ->where('chart_id', $code->code)
            //     ->first();

            // if ($checkCredit) {
            //     $checkCredit->update($credit);
            // } else {
            //     GeneralLedger::create($credit);
            // }

            try {
                $customer->update($data);
                toast('Customer Updated!', 'success');
                DB::commit();
            } catch (\Exception $e) {
                return $e->getMessage();
                toast($e->getMessage(), 'error');
                DB::rollBack();
            }
            return redirect()->route('customer.view', $customer->profession_id);
        } else {
            toast('Please Check Status!', 'warning');
            return back();
        }
    }

    // Delete
    public function delete(CustomerCard $customer)
    {
        try {
            $customer->delete();
            toast('Customer Deleted!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
}
