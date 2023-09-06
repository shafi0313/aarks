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
            'client_id'         => ['required', 'exists:clients,id'],
            'profession_id'     => ['required', 'exists:professions,id'],
            'status'            => ['required', 'integer'],
            'type'              => ['required', 'integer'],
            'customer_type'     => ['required', 'string', 'min:1', 'max:191'],
            'customer_ref'      => ['required', 'string', 'min:1', 'max:191'],
            'name'              => ['required', 'string', 'min:1', 'max:191'],
            'b_address'         => ['required', 'string', 'min:1'],
            'b_city'            => ['required', 'string', 'min:1', 'max:191'],
            'b_state'           => ['required', 'string', 'min:1', 'max:191'],
            'b_postcode'        => ['required', 'string', 'min:1', 'max:191'],
            'b_country'         => ['required', 'string', 'min:1', 'max:191'],
            's_address'         => ['nullable', 'string', 'min:1'],
            's_city'            => ['nullable', 'string', 'min:1', 'max:191'],
            's_state'           => ['nullable', 'string', 'min:1', 'max:191'],
            's_postcode'        => ['nullable', 'string', 'min:1', 'max:191'],
            's_country'         => ['nullable', 'string', 'min:1', 'max:191'],
            'phone'             => ['nullable', 'string', 'min:1', 'max:191'],
            'mobile'            => ['nullable', 'string', 'min:1', 'max:191'],
            'email'             => ['nullable', 'string', 'min:1', 'max:191'],
            'xxx'               => ['nullable', 'string', 'min:1', 'max:191'],
            'greeting'          => ['nullable', 'string', 'min:1', 'max:191'],
            'inv_layout'        => ['required', 'string', 'min:1', 'max:191'],
            'inv_delivery'      => ['required', 'string', 'min:1', 'max:191'],
            'barcode_price'     => ['nullable', 'string', 'min:1', 'max:191'],
            'shipping_method'   => ['nullable', 'string', 'min:1', 'max:191'],
            'inv_rate'          => ['nullable', 'numeric'],
            'card_limit'        => ['nullable', 'numeric'],
            'abn'               => ['nullable', 'string', 'min:1', 'max:191'],
            'gst_code'          => ['nullable', 'string', 'min:1', 'max:191'],
            'freight_code'      => ['nullable', 'string', 'min:1', 'max:191'],
            'contact_person'    => ['nullable', 'string', 'min:1', 'max:191'],
            'inv_comment'       => ['nullable', 'string', 'min:1', 'max:191'],
            'early_discount'    => ['nullable', 'string', 'min:1', 'max:191'],
            'late_fee'          => ['nullable', 'string', 'min:1', 'max:191'],
            'overall_discount'  => ['nullable', 'string', 'min:1', 'max:191'],
            'payment_due'       => ['nullable', 'string', 'min:1', 'max:191'],
            'days'              => ['nullable', 'string', 'min:1', 'max:191'],
            'by_date'           => ['nullable', 'string', 'min:1', 'max:191'],
            'after_date'        => ['nullable', 'string', 'min:1', 'max:191'],
            'payment_method'    => ['nullable', 'string', 'min:1', 'max:191'],
            'payment_note'      => ['nullable', 'string', 'min:1', 'max:191'],
            'bsb_table_id'      => ['nullable', 'integer', 'min:0', 'max:18446744073709551615'],
            'opening_blnc'      => ['nullable', 'string', 'min:1', 'max:191'],
            'opening_blnc_date' => ['nullable', 'string', 'min:1', 'max:191'],
            'credit_account'    => ['nullable', 'string', 'min:1', 'max:191']

            // "type"              => 'required',
            // "client_id"         => 'required',
            // "profession_id"     => 'required',
            // "customer_type"     => 'required',
            // "status"            => 'required',
            // "name"              => 'required|string',
            // "customer_ref"      => 'required',
            // "b_address"         => 'required',
            // "b_city"            => 'required',
            // "b_state"           => 'required',
            // "b_postcode"        => 'required',
            // "b_country"         => 'required',
            // "b_country"         => 'required',
            // "phone"             => 'sometimes',
            // "mobile"            => 'sometimes',
            // "email"             => 'sometimes',
            // "s_address"         => 'sometimes',
            // "s_city"            => 'sometimes',
            // "s_state"           => 'sometimes',
            // "s_postcode"        => 'sometimes',
            // "s_country"         => 'sometimes',
            // "xxx"               => 'sometimes',
            // "greeting"          => 'sometimes',
            // "inv_layout"        => 'required',
            // "inv_delivery"      => 'required',
            // "barcode_price"     => 'sometimes',
            // "shipping_method"   => 'sometimes',
            // "inv_rate"          => 'sometimes',
            // "card_limit"        => 'sometimes',
            // "abn"               => 'sometimes',
            // "gst_code"          => 'sometimes',
            // "freight_code"      => 'sometimes',
            // "contact_person"    => 'sometimes',
            // "inv_comment"       => 'sometimes',
            // "early_discount"    => 'sometimes',
            // "late_fee"          => 'sometimes',
            // "overall_discount"  => 'sometimes',
            // "payment_due"       => 'sometimes',
            // "by_date"           => 'sometimes',
            // "after_date"        => 'sometimes',
            // "payment_method"    => 'sometimes',
            // "bsb_table_id"      => 'sometimes',
            // "opening_blnc"      => 'required',
            // "opening_blnc_date" => 'sometimes',
            // "credit_account"    => 'sometimes',
            // "days"              => 'sometimes',            
        ];
    }
}
