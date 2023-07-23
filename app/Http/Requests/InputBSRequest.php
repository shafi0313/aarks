<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InputBSRequest extends FormRequest
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
            'account_code'  => 'required',
            'date'          => 'required|date_format:d/m/Y',
            'debit'         => 'required_without:credit',
            'credit'        => 'required_without:debit',
            'client_id'     => 'required|exists:clients,id',
            'profession_id' => 'required|exists:professions,id',
            'gst_code'      => 'required|in:'. implode(',', aarks('gst_code')),
            'narration'     => 'required'
        ];
    }
}
