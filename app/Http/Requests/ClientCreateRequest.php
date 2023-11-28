<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('admin.client.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company'               => 'sometimes|max:191',
            'contact_person'        => 'sometimes|max:191',
            'first_name'            => 'sometimes|max:191',
            'last_name'             => 'sometimes|max:191',
            'birthday'              => 'sometimes|max:191',
            'phone'                 => 'required|max:191|unique:clients',
            'email'                 => 'required|max:191|email|unique:clients',
            'abn_number'            => 'required|integer|digits:11|unique:clients',
            'branch'                => 'required|integer|digits:1',
            'tax_file_number'       => 'nullable|string|min:6|max:10',
            'charitable_number'     => 'nullable|string|max:191',
            'iran_number'           => 'nullable|string|max:191',
            'street_address'        => 'required|max:191',
            'suburb'                => 'required|max:191',
            'state'                 => 'required|max:191',
            'post_code'             => 'required|min:3',
            'country'               => 'required|max:191',
            'director_name'         => 'sometimes|max:191',
            'director_address'      => 'sometimes|max:191',
            'agent_name'            => 'nullable|string|max:191',
            'agent_address'         => 'nullable|string|max:191',
            'agent_number'          => 'nullable|max:191',
            'agent_abn_number'      => 'nullable|integer|digits:11',
            'auditor_name'          => 'sometimes|max:191',
            'auditor_address'       => 'sometimes|max:191',
            'auditor_phone'         => 'nullable|max:191',
            'password'              => 'required|string|min:8|max:191|confirmed',
            'password_confirmation' => 'required|string|same:password',
            'services'              => 'required|max:191',
            'is_gst_enabled'        => 'required|integer|max:1',
            'gst_method'            => 'required|integer|max:11',
            'professions'           => 'required|max:191',
            'website'               => 'nullable|string|max:191',
        ];
    }
}
