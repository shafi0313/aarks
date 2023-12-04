<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\ClientPaymentList;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plans = Subscription::all();
        return view('admin.subscription.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.subscription.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSubscriptionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubscriptionRequest $request)
    {
        $plan = $request->validated();
        try {
            Subscription::create($plan);
            toast('Subscription created successfully', 'success');
        } catch (\Exception $e) {
            toast('Oops! Something went wrong with your subscription. Please try again.', 'danger');
            #return $e->getMessage();
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subscription  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $plan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subscription  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $plan)
    {
        return view('admin.subscription.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubscriptionRequest  $request
     * @param  \App\Subscription  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $plan)
    {
        $req_plan = $request->validated();
        try {
            $plan->update($req_plan);
            toast('Subscription updated successfully', 'success');
        } catch (\Exception $e) {
            toast('Oops! Something went wrong with your subscription. Please try again.', 'danger');
            #return $e->getMessage();
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subscription  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $plan)
    {
        try {
            $plan->delete();
            toast('Subscription deleted successfully', 'success');
        } catch (\Exception $e) {
            toast('Oops! Something went wrong with your subscription. Please try again.', 'danger');
            #return $e->getMessage();
        }
        return redirect()->back();
    }


    // Client Side Actions

    public function upgrade()
    {
        $plans = Subscription::get(['id','name','interval','amount']);
        return view('frontend.update_account.index', compact('plans'));
    }
    public function upgradeRequest(Request $request)
    {
        // return now()->addMonth($request->duration)->format('D m, Y h:i:s');
        $data = $request->validate([
            'client_id'       => 'required|integer',
            'subscription_id' => 'required|integer',
            'duration'        => 'required|string',
            'amount'          => 'required|numeric',
            'message'         => 'sometimes|string',
            'rcpt'            => 'required|max:3600',
        ]);
        $plan = Subscription::findOrFail($request->subscription_id);

        $data['sales_quotation']    = $plan->sales_quotation * $request->duration;
        $data['purchase_quotation'] = $plan->purchase_quotation * $request->duration;
        $data['invoice']            = $plan->invoice * $request->duration;
        $data['bill']               = $plan->bill * $request->duration;
        $data['receipt']            = $plan->receipt * $request->duration;
        $data['payment']            = $plan->payment * $request->duration;
        $data['payslip']            = $plan->payslip * $request->duration;
        $data['discount']           = $plan->discount;
        $data['access_report']      = $plan->access_report;
        $data['customer_support']   = $plan->customer_support;
        $data['started_at']         = now();
        $data['expire_at']          = now()->addMonth($request->duration);

        $rcpt = $request->file('rcpt');
        if ($request->hasFile('rcpt')) {
            $rcptNew  = "payment_rcpt_" . Str::random(5) . '.' . $rcpt->getClientOriginalExtension();
            if ($rcpt->isValid()) {
                $rcpt->storeAs('/rcpt', $rcptNew);
                $data['rcpt']  = '/uploads/rcpt/' . $rcptNew;
            }
        }
        try {
            ClientPaymentList::create($data);
            toast('Request Submitted wait for approval!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
    public function paymentList()
    {
        $payLists = ClientPaymentList::with('subscription')->where('client_id', client()->id)->get();
        return view('frontend.profile.payment_list', compact(['payLists']));
    }
}
