<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return [
        //     "client_id"  => $this->client_id,
        //     "pack_name"  => $this->pack_name,
        //     "amount"     => $this->amount,
        //     "duration"   => $this->duration,
        //     "message"    => $this->message,
        //     "rcpt"       => asset($this->rcpt),
        //     "status"     => $this->status,
        //     "is_expire"  => $this->is_expire,
        //     "started_at" => $this->started_at,
        //     "expire_at"  => $this->expire_at,
        // ];
        return [
            "client_id"         => $this->client_id,
            "subscription_id"   => $this->subscription_id,
            "amount"            => $this->amount,
            "duration"          => $this->duration,
            "message"           => $this->message,
            "rcpt"              => asset($this->rcpt),
            "status"            => $this->status,
            "is_expire"         => $this->is_expire,
            "sales_quotation"   => $this->sales_quotation,
            "purchase_quotation"=> $this->purchase_quotation,
            "invoice"           => $this->invoice,
            "bill"              => $this->bill,
            "receipt"           => $this->receipt,
            "payment"           => $this->payment,
            "payslip"           => $this->payslip,
            "discount"          => $this->discount,
            "access_report"     => $this->access_report,
            "customer_support"  => $this->customer_support,
            "started_at"        => $this->started_at,
            "expire_at"         => $this->expire_at,
        ];
    }
}
