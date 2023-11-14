<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DedotrQuoteRequest extends FormRequest
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
            "client_id"        => 'sometimes',
            "tran_id"          => 'sometimes',
            // "inv_id"          => 'sometimes',
            "source"           => 'required',
            "profession_id"    => 'sometimes',
            "customer_card_id" => 'required',
            "start_date"       => 'required',
            "end_date"         => 'sometimes',
            "inv_no"           => 'required',
            "your_ref"         => 'sometimes',
            "due_date"         => 'sometimes',
            "our_ref"          => 'sometimes',
            "quote_terms"      => 'sometimes',
            "job_title"        => 'sometimes',
            "job_des"          => 'sometimes',
            "item_name"        => 'sometimes',
            "alige"            => 'sometimes',
            "item_id"          => 'sometimes',
            "quantity"         => 'sometimes',
            "rate"             => 'sometimes',
            "amount"           => 'sometimes',
            "price"            => 'sometimes',
            "disc_rate"        => 'sometimes',
            "disc_amount"      => 'sometimes',
            "freight_charge"   => 'sometimes',
            "chart_id"         => 'required',
            "is_tax"           => 'required',
            "tax_rate"         => 'required',
            "bank_account"     => 'sometimes',
            "payment_amount"   => 'sometimes',
            "total_amount"     => 'sometimes',
            "totalamount"      => 'sometimes',
            "gst_amt"          => 'sometimes',
            "gst_amt_subtotal" => 'sometimes',
            "sub_val"          => 'sometimes',
        ];
    }
}
