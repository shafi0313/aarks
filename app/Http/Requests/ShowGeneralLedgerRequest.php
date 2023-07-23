<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowGeneralLedgerRequest extends FormRequest
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
            'start_date'   => 'required|date_format:d/m/Y',
            'end_date'     => 'required|date_format:d/m/Y',
            'from_account' => 'required',
            'to_account'   => 'required|gte:from_account',
            'submit'       => 'nullable',
        ];
    }
}
