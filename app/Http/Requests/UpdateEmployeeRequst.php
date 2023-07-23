<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequst extends FormRequest
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
            "status"             => 'required',
            "profession_id"      => 'sometimes',
            "client_id"          => 'sometimes',
            "first_name"         => 'required',
            "dob"                => 'required|date',
            "last_name"          => 'required',
            "gender"             => 'required',
            "address"            => 'required',
            "city"               => 'required',
            "state"              => 'required',
            "post_code"          => 'required',
            "country"            => 'required',
            "phone"              => 'sometimes',
            "mobile"             => 'required',
            // "email"              => 'sometimes|email',
            "email"              => 'sometimes',
            "start_date"         => 'required|date',
            "term_date"          => 'sometimes',
            "emp_basis"          => 'required',
            "emp_category"       => 'required',
            "emp_status"         => 'required',
            "emp_classification" => 'required',
            "pay_basis"          => 'required',
            "annual_salary"      => 'sometimes',
            "hourly_rate"        => 'sometimes',
            "pay_frequency"      => 'required',
            "hour_pay_frequency" => 'required',
            "netpay_wages_ac"    => 'required',
            "tw_exp_ac"          => 'required',
            "tw_payable_ac"      => 'required',
            "tax_number"         => 'required',
            "tax_table"          => 'required',
            "wv_rate"            => 'sometimes',
            "total_rebate"       => 'sometimes',
            "extra_tax"          => 'sometimes',
            "wages"              => 'required|array',   //arry
            "payment_method"     => 'sometimes',
            "payment_ac"         => 'sometimes',
            "payment_note"       => 'sometimes',
            "leave"              => 'required|array',   //arry
            "sup_fund"           => 'sometimes',
            "sup_exp_ac"         => 'required',
            "emp_membership"     => 'sometimes',
            "sup_payable_ac"     => 'required',
            "superannuation"     => 'required|array',   //arry
            "link_dd_ac"         => 'required',
            "deduction"          => 'required|array',   //arry
        ];

    }
}
