<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashBookCreateRequest extends FormRequest
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
            "source"         => 'sometimes',
            "ac_type"        => 'sometimes',
            "cash_office_id" => 'required',
            "profession_id"  => 'required',
            "client_id"      => 'required',
            "chart_id"       => 'required',
            "narration"      => 'required',
            "gst_code"       => 'required',
            "recevied"       => 'sometimes',
            "payment"        => 'sometimes',
            "inventory"      => 'sometimes',
            "gst"            => 'required'
        ];
    }
}
