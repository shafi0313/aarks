<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCustomerRequest extends FormRequest
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
            "type"              => 'required',
            "client_id"         => 'required',
            "profession_id"     => 'required',
            "customer_type"     => 'required',
            "status"            => 'required',
            "name"              => 'required',
            "customer_ref"      => 'required',
            "b_address"         => 'required',
            "b_city"            => 'required',
            "b_state"           => 'required',
            "b_postcode"        => 'required',
            "b_country"         => 'required',
            "b_country"         => 'required',
            "phone"             => 'sometimes',
            "mobile"            => 'sometimes',
            "email"             => 'sometimes',
            "s_address"         => 'sometimes',
            "s_city"            => 'sometimes',
            "s_state"           => 'sometimes',
            "s_postcode"        => 'sometimes',
            "s_country"         => 'sometimes',
            "xxx"               => 'sometimes',
            "greeting"          => 'sometimes',
            "inv_layout"        => 'required',
            "inv_delivery"      => 'required',
            "barcode_price"     => 'sometimes',
            "shipping_method"   => 'sometimes',
            "inv_rate"          => 'sometimes',
            "card_limit"        => 'sometimes',
            "abn"               => 'sometimes',
            "gst_code"          => 'sometimes',
            "freight_code"      => 'sometimes',
            "contact_person"    => 'sometimes',
            "inv_comment"       => 'sometimes',
            "early_discount"    => 'sometimes',
            "late_fee"          => 'sometimes',
            "overall_discount"  => 'sometimes',
            "payment_due"       => 'sometimes',
            "by_date"           => 'sometimes',
            "after_date"        => 'sometimes',
            "payment_method"    => 'sometimes',
            "bsb_table_id"      => 'sometimes',
            "opening_blnc"      => 'required',
            "opening_blnc_date" => 'sometimes',
            "credit_account"    => 'sometimes',
            "days"              => 'sometimes',
        ];
    }
}
