<?php

namespace App\Http\Controllers\Frontend\Setting;

use App\Models\Client;
use App\Models\Service;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\ClientAccountCode;
use App\Models\Frontend\BsbTable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Actions\ClientActions\EditClient;
use App\Http\Requests\ClientUpdateRequest;
use App\Actions\AccountCodeActions\CopyClientAccountCode;
use App\Actions\AccountCodeActions\DeleteClientAccountCode;

class ProfileController extends Controller
{
    public function index()
    {
        $client      = Client::with('professions')->find(client()->id);
        $bsbs        = BsbTable::with('profession')->where('client_id', $client->id)->get();
        $professions = Profession::select('id', 'name')->get();
        $services    = Service::select()->select('name', 'id')->get();
        return view('frontend.profile.index', compact(['client','bsbs','professions','services']));
    }

    public function clientProfileUpdate(Request $request, Client $client, EditClient $editClient, CopyClientAccountCode $copyClientAccountCode, DeleteClientAccountCode $deleteClientAccountCode)
    {
        // return $editClient;
        $data = $request->validate([
            'company'               => 'sometimes',
            'contact_person'        => 'sometimes',
            'first_name'            => 'required',
            'last_name'             => 'required',
            'birthday'              => 'sometimes',
            'phone'                 => 'required|digits:10|unique:clients,phone,'.$client->id,
            'email'                 => 'required|email|unique:clients,email,'.$client->id,
            'abn_number'            => 'required|integer|digits:11|unique:clients,abn_number,'.$client->id,
            'branch'                => 'required|integer|digits:1',
            'tax_file_number'       => 'nullable|string|min:6|max:10',
            'street_address'        => 'required',
            'suburb'                => 'required',
            'state'                 => 'required',
            'post_code'             => 'required|min:3',
            'country'               => 'required',
            'director_name'         => 'sometimes',
            'director_address'      => 'sometimes',
            'agent_name'            => 'sometimes',
            'agent_address'         => 'sometimes',
            'agent_number'          => 'nullable|integer|digits:10',
            'agent_abn_number'      => 'nullable|integer|digits:11',
            'auditor_name'          => 'sometimes',
            'auditor_address'       => 'sometimes',
            'auditor_phone'         => 'nullable|integer|digits:10',
            'password'              => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|same:password',
            'services'              => 'required',
            'is_gst_enabled'        => 'required',
            'gst_method'            => 'sometimes',
            'professions'           => 'required',
            'website'               => 'sometimes',
        ]);

        if (empty($request->password)) {
            unset($data['password']);
        }

        // $data = $this->prepareClientDataForUpdate($request);

        DB::beginTransaction();

        $previous_professions = $client->professions->pluck('id')->toArray();
        $profession_id_to_delete = array_diff($previous_professions, $data['professions']);
        $profession_id_to_add = array_diff($data['professions'], $previous_professions);
        // return ClientAccountCode::where('client_id', $client->id)
        //    ->whereIn('profession_id', $profession_id_to_delete)->get();


        try {
            $editClient->setInstance($client)->setData($data)->execute();
            $copyClientAccountCode->setData(['profession_id' => $profession_id_to_add,'client_id' => $client->id])->execute();
            $deleteClientAccountCode->setData($profession_id_to_delete, $client->id)->execute();
            toast('Client Successfully Updated', 'success');

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
            toast($exception->getMessage(), 'error');
        }

        return redirect()->back();
    }

    public function prepareClientDataForUpdate(ClientUpdateRequest $request)
    {
        $data = [
            'company'          => $request->company,
            'contact_person'   => $request->contact_person,
            'first_name'       => $request->first_name,
            'last_name'        => $request->last_name,
            'birthday'         => $request->birthday,
            'phone'            => $request->phone,
            'abn_number'       => $request->abn_number,
            'branch'           => $request->branch,
            'tax_file_number'  => $request->tax_file_number,
            'street_address'   => $request->street_address,
            'state'            => $request->state,
            'post_code'        => $request->post_code,
            'director_name'    => $request->director_name,
            'director_address' => $request->director_address,
            'agent_name'       => $request->agent_name,
            'agent_address'    => $request->agent_address,
            'agent_number'     => $request->agent_number,
            'agent_abn_number' => $request->agent_abn_number,
            'auditor_name'     => $request->auditor_name,
            'auditor_address'  => $request->auditor_address,
            'auditor_phone'    => $request->auditor_phone,
            'email'            => $request->email,
            'suburb'           => $request->suburb,
            'country'          => $request->country,
            'gst_method'       => $request->gst_method,
            'is_gst_enabled'   => $request->is_gst_enabled??0,
            'password'         => bcrypt($request->password),
            'services'         => $request->services,
            'professions'      => $request->professions,
            'website'          => $request->website,
        ];

        if (empty($request->password)) {
            unset($data['password']);
        }

        return $data;
    }
    public function uploadLogo()
    {
        return view('frontend.profile.upload_logo');
    }
    public function storeLogo(Request $request)
    {
        $client = Client::findOrFail($request->client_id);
        $logo = $request->file('logo');
        if ($request->hasFile('logo')) {
            $logoNew  = "client_logo_" . time() . '.' . $logo->getClientOriginalExtension();
            if ($logo->isValid()) {
                $path = public_path() . $client->logo;
                if ($client->logo) {
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                }
                $logo->storeAs('/logo', $logoNew);
                $data['logo']  = '/uploads/logo/' . $logoNew;
            }
        }
        try {
            $client->update($data);
            toast('Logo Updated!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
}
