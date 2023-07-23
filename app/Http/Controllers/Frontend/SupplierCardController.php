<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use App\Http\Controllers\Controller;
use App\Models\Frontend\CustomerCard;
use App\Http\Requests\AddCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

class SupplierCardController extends Controller
{
    public function index()
    {
        //
    }
    public function show(Profession $supplier)
    {
        $profession =  $supplier;
        $creditCodes = ClientAccountCode::where(function ($q) {
            $q->where('code', 'like', '954%')->orWhere('code', 'like', '99%');
        })
        ->where('client_id', client()->id)
        ->where('profession_id', $profession->id)
        ->orderBy('code')
        ->get();

        return view('frontend.add_card.supplier', compact('profession', 'creditCodes'));
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
            $supplier = CustomerCard::create($data);
        } else {
            toast('Please Check Status!', 'warning');
        }

        if ($request->opening_blnc != '' && $request->credit_account != '' && $request->opening_blnc_date != '') {
            $code = ClientAccountCode::find($request->credit_account);
            $tran_date = Carbon::parse($request->opening_blnc_date);
            $ledger = $credit = [
                'chart_id'               => 911999,
                'date'                   => $tran_date,
                'narration'              => 'Customer Opening balance',
                'source'                 => 'OPN',
                'debit'                  => 0,
                'credit'                 => 0,
                'gst'                    => 0,
                'balance'                => $request->opening_blnc,
                'balance_type'           => 2,
                'transaction_for'        => 'OPN',
                'client_id'              => $request->client_id,
                'profession_id'          => $request->profession_id,
                'client_account_code_id' => ClientAccountCode::where('code', 911999)
                                            ->where('client_id', $request->client_id)
                                            ->where('profession_id', $request->profession_id)
                                            ->first()->id,
                'transaction_id'         => $tran_id = transaction_id('OPN'),
            ];
            $credit['chart_id'] = $code->code;
            $credit['client_account_code_id'] = $code->id;
            $credit['balance_type'] = 1;
            if ($request->opening_blnc < 1) {
                $credit['debit']        = 0;
                $credit['credit']       = abs($request->opening_blnc);

                $ledger['credit']       = 0;
                $ledger['debit']        = abs($request->opening_blnc);
            } else {
                $credit['credit']       = 0;
                $credit['debit']        = $request->opening_blnc;

                $ledger['debit']        = 0;
                $ledger['credit']       = $request->opening_blnc;
            }
            GeneralLedger::create($ledger);
            GeneralLedger::create($credit);
        }
        try {
            toast('Supplier Created!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
    public function view($profession)
    {
        $client = client()->id;
        $customers = CustomerCard::where('type', 2)->where('client_id', $client)->where('profession_id', $profession)->get();
        return view('frontend.card_list.supplier', compact('customers'));
    }
    public function edit(CustomerCard $supplier)
    {
        $creditCodes = ClientAccountCode::where(function ($q) {
            $q->where('code', 'like', '954%')->orWhere('code', 'like', '99%');
        })
        ->where('client_id', client()->id)
        ->where('profession_id', $supplier->profession_id)
        ->orderBy('code')
        ->get();

        return view('frontend.card_list.update_suplier', compact('supplier', 'creditCodes'));
    }
    public function update(UpdateCustomerRequest $request, CustomerCard $supplier)
    {
        $data = $request->validated();

        if ($data['status']==1) {
            if ($request->opening_blnc != '' && $request->credit_account != '' && $request->opening_blnc_date != '') {
                $code = ClientAccountCode::find($request->credit_account);
                $tran_date = Carbon::parse($request->opening_blnc_date);
                $tran_id = transaction_id('OPN');

                $ledger = $credit = [
                    'chart_id'               => 911999,
                    'date'                   => $tran_date,
                    'narration'              => 'Customer Opening balance UP',
                    'source'                 => 'OPN',
                    'debit'                  => 0,
                    'credit'                 => abs($request->opening_blnc),
                    'gst'                    => 0,
                    'balance'                => abs($request->opening_blnc),
                    'balance_type'           => 2,
                    'transaction_for'        => 'OPN',
                    'client_id'              => $supplier->client_id,
                    'profession_id'          => $supplier->profession_id,
                    'client_account_code_id' => ClientAccountCode::where('code', 911999)
                                                ->where('client_id', $supplier->client_id)
                                                ->where('profession_id', $supplier->profession_id)
                                                ->first()->id,
                    'transaction_id'         => $tran_id,
                ];
                $credit['chart_id']               = $code->code;
                $credit['client_account_code_id'] = $code->id;
                $credit['balance_type']           = 1;
                $credit['debit']                  = 0;

                if ($request->opening_blnc < 0) {
                    $credit['debit']       = abs($request->opening_blnc);
                } else {
                    $credit['debit']        = $request->opening_blnc;
                }

                if ($code->code == 999999) {
                    if ($request->opening_blnc < 0) {
                        $credit['debit'] = $credit['balance'] = ($request->opening_blnc);
                    } else {
                        $credit['debit']  = $credit['balance'] = - $request->opening_blnc;
                    }
                }

                $checkLedger = GeneralLedger::where('source', 'OPN')
                        ->where('transaction_id', $tran_id)
                        ->where('chart_id', 911999)
                        ->first();
                if ($checkLedger != '') {
                    $checkLedger->update($ledger);
                } else {
                    GeneralLedger::create($ledger);
                }
                $checkCredit = GeneralLedger::where('source', 'OPN')
                        ->where('transaction_id', $tran_id)
                        ->where('chart_id', '!=', 911999)
                        // ->where('chart_id', $code->code)
                        ->first();
                if ($checkCredit != '') {
                    $checkCredit->update($credit);
                } else {
                    GeneralLedger::create($credit);
                }
            }
            try {
                $supplier->update($data);
                toast('Supplier Updated!', 'success');
            } catch (\Exception $e) {
                toast($e->getMessage(), 'error');
            }
            return redirect()->route('supplier.view', $supplier->profession_id);
        } else {
            toast('Please Check Status!', 'warning');
            return back();
        }
    }
    public function delete(CustomerCard $supplier)
    {
        try {
            $supplier->delete();
            toast('Customer Deleted!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
}
