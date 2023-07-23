<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHelpDeskRequest extends FormRequest
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
            "name"        => "required|string|unique:help_desks,name," . $this->helpdesk->id,
            "title"       => "nullable|string",
            "parent_id"   => "nullable|string",
            "description" => "nullable|string",
            "type"        => "nullable|string",
            "thumbnail"   => "nullable|image|mimes:png,jpg,jpeg|max:2048",
        ];
    }
}
