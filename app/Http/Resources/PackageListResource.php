<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageListResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            "id"                 => $this->id,
            "name"               => $this->name,
            "description"        => $this->description,
            "amount"             => $this->amount,
            "interval"           => $this->interval,
            "sales_quotation"    => $this->sales_quotation,
            "purchase_quotation" => $this->purchase_quotation,
            "invoice"            => $this->invoice,
            "bill"               => $this->bill,
            "receipt"            => $this->receipt,
            "payment"            => $this->payment,
            "payslip"            => $this->payslip,
            "discount"           => $this->discount,
            "access_report"      => $this->access_report,
            "customer_support"   => $this->customer_support,
        ];

        /*
        "id": 1,
        "name": "ENCHANCER",
        "description": null,
        "amount": 14.99,
        "interval": 30,
        "sales_quotation": 10,
        "purchase_quotation": 0,
        "invoice": 10,
        "bill": 0,
        "receipt": 10,
        "payment": 0,
        "payslip": 0,
        "discount": 10,
        "access_report": 1,
        "customer_support": 1,
        "created_at": "2022-08-13T02:26:23.000000Z",
        "updated_at": "2022-08-13T02:26:23.000000Z"
        */
    }
}
