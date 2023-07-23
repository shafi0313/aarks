<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHelpDeskRequest extends FormRequest
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
            "name"        => "required|string|unique:help_desks,name",
            "title"       => "nullable|string",
            "parent_id"   => "required|string",
            "description" => "required|string",
            "type"        => "required|string",
            "thumbnail"   => "nullable|image|mimes:png,jpg,jpeg|max:2048",
        ];
    }
}
