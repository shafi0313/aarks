<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ClientPaymentList;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\PaymentCollection;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PackageListResource;
use App\Http\Resources\PackageListCollection;

class ClientPaymentApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->key == 'vuJBgi3rjad3xymO8eraAMcnzn6w2zVIaVupFYn7') {
            $payments = ClientPaymentList::where('client_id', $request->client_id)->paginate($request->per_page ?? 10);
            if ($payments->count()) {
                return new PaymentCollection($payments);
            }
            return response()->json(['message' => 'Payments records not found.'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->key == 'vuJBgi3rjad3xymO8eraAMcnzn6w2zVIaVupFYn7') {
            // return $request;
            $validator = Validator::make($request->all(), [
                "client_id" => "required|string",
                "key"      => "required|string",
                "package"  => "required|string",
                "duration" => "required|string",
                "amount"   => "required|string",
                "note"     => "nullable|string",
                "receipt"  => "required|file|mimes:png,jpg,jpeg,pdf|max:2024",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'status' => Response::HTTP_BAD_REQUEST,
                ], Response::HTTP_BAD_REQUEST);
            }
            $data = [
                'client_id'       => $request->client_id,
                'subscription_id' => $request->package,
                'duration'        => floor($request->duration / 30),
                'amount'          => $request->amount,
                'message'         => $request->note,
                'started_at'      => now(),
                'expire_at'       => now()->addDays($request->duration),
            ];

            $plan = Subscription::find($request->package);
            if (is_null($plan)) {
                return response()->json([
                                        'errors' => 'Subscription plan not found.',
                                        'status' => Response::HTTP_BAD_REQUEST,
                                    ], Response::HTTP_BAD_REQUEST);
            }

            $data['sales_quotation']    = $plan->sales_quotation;
            $data['purchase_quotation'] = $plan->purchase_quotation;
            $data['invoice']            = $plan->invoice;
            $data['bill']               = $plan->bill;
            $data['receipt']            = $plan->receipt;
            $data['payment']            = $plan->payment;
            $data['payslip']            = $plan->payslip;
            $data['discount']           = $plan->discount;
            $data['access_report']      = $plan->access_report;
            $data['customer_support']   = $plan->customer_support;

            if ($request->hasFile('receipt')) {
                $rcpt = $request->file('receipt');
                $rcptNew  = "payment_rcpt_" . Str::random(5) . '.' . $rcpt->getClientOriginalExtension();
                if ($rcpt->isValid()) {
                    $rcpt->storeAs('/rcpt', $rcptNew);
                    $data['rcpt']  = '/uploads/rcpt/' . $rcptNew;
                }
            }
            try {
                ClientPaymentList::create($data);
                return response()->json(['message' => 'Request Submitted wait for approval!']);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        }
    }

    /**
     * Display All packages information resource.
     *
     * @param  \App\Subscription  $Subscription
     * @return \Illuminate\Http\Response
     */
    public function packageList(Request $request)
    {
        if ($request->key == 'vuJBgi3rjad3xymO8eraAMcnzn6w2zVIaVupFYn7') {
            $pacages = Subscription::paginate($request->per_page ?? 10);
            if ($pacages->count()) {
                return new PackageListCollection($pacages);
            }
            return response()->json(['message' => 'Payments records not found.'], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ClientPaymentList  $ClientPaymentList
     * @return \Illuminate\Http\Response
     */
    public function show(ClientPaymentList $ClientPaymentList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClientPaymentList  $ClientPaymentList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientPaymentList $ClientPaymentList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ClientPaymentList  $ClientPaymentList
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientPaymentList $ClientPaymentList)
    {
        //
    }
}
