<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MasterChartAccountCodeCreateRequest extends FormRequest
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
            'industry_category_id' => 'required',
            'category' => 'required',
            'sub_category' => 'required',
            'additional_category' => 'required',
            'account_code' => 'required|unique:account_codes,code|string|min:3|max:3',
            'type' => 'required',
            'account_name' => 'required',
            'gst_code' => 'required'
        ];
    }
}
