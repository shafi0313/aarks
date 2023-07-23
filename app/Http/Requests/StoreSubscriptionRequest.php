<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name"              => "required|string",
            "description"       => "nullable|string",
            "amount"            => "required|numeric",
            "interval"          => "required|numeric",
            "sales_quotation"   => "nullable|numeric",
            "purchase_quotation"=> "nullable|numeric",
            "invoice"           => "nullable|numeric",
            "bill"              => "nullable|numeric",
            "receipt"           => "nullable|numeric",
            "payment"           => "nullable|numeric",
            "payslip"           => "nullable|numeric",
            "discount"          => "nullable|numeric",
            "access_report"     => "nullable|numeric",
            "customer_support"  => "nullable|numeric",
        ];
    }
}
