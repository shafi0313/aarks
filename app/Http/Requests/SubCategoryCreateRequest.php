<?php namespace App\Http\Requests;

use App\Rules\DuplicateAccountCodeCategory;
use Illuminate\Foundation\Http\FormRequest;

class SubCategoryCreateRequest extends FormRequest
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

           'industry_category' => 'required|array',
            'category' => 'required',
            'sub_category_name' => 'required',
            'single_account_code' => ['required', 'string', 'min:1', 'max:1', new DuplicateAccountCodeCategory(2)]
        ];
    }
}
