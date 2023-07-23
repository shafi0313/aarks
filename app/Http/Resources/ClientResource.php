<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "client_id"        => $this->id,
            "email"            => $this->email,
            "fullname"         => $this->fullname,
            "company"          => $this->company,
            "contact_person"   => $this->contact_person,
            "first_name"       => $this->first_name,
            "last_name"        => $this->last_name,
            "birthday"         => $this->birthday,
            "phone"            => $this->phone,
            "abn_number"       => $this->abn_number,
            "branch"           => $this->branch,
            "tax_file_number"  => $this->tax_file_number,
            "street_address"   => $this->street_address,
            "suburb"           => $this->suburb,
            "state"            => $this->state,
            "address"          => $this->address,
            "post_code"        => $this->post_code,
            "country"          => $this->country,
            "director_name"    => $this->director_name,
            "director_address" => $this->director_address,
            "agent_name"       => $this->agent_name,
            "agent_address"    => $this->agent_address,
            "agent_number"     => $this->agent_number,
            "agent_abn_number" => $this->agent_abn_number,
            "auditor_name"     => $this->auditor_name,
            "auditor_address"  => $this->auditor_address,
            "auditor_phone"    => $this->auditor_phone,
            "is_gst_enabled"   => $this->is_gst_enabled,
            "gst_method"       => $this->gst_method,
            "logo"             => $this->logo,
        ];
        // return parent::toArray($request);
    }
}
