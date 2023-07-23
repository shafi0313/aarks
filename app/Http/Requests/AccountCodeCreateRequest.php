<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountCodeCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('admin.account-code.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'profession_id'        => 'required',
            'industry_category_id' => 'required|array',
            'category'             => 'required',
            'sub_category'         => 'required',
            'additional_category'  => 'required',
            'account_code'         => 'required|unique:account_codes,code|string|min:3|max:3',
            'type'                 => 'required',
            'account_name'         => 'required',
            'gst_code'             => 'required'
        ];
    }
}
