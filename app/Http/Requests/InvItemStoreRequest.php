<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvItemStoreRequest extends FormRequest
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
            "profession_id"          => 'required',
            "client_id"              => 'required',
            "category"               => 'required',
            "item_number"            => 'required',
            "item_name"              => 'required',
            "type"                   => 'required',
            "status"                 => 'required',
            "bin_number"             => 'sometimes',
            "barcode_number"         => 'sometimes',
            "measure_id"             => 'required',
            "price"                  => 'required',
            "gst_code"               => 'required',
            "customer_card_id"       => 'required',
            "client_account_code_id" => 'required',
            "qun_date"               => 'sometimes',
            "qun_hand"               => 'sometimes',
            "qun_rate"               => 'sometimes',
            "alige"                  => 'sometimes',
            "current_value"          => 'sometimes'
        ];
    }
}
