<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Service;
use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\ClientActions\AddClient;
use RealRashid\SweetAlert\Facades\Alert;
use App\Actions\ClientActions\EditClient;
use App\Http\Requests\ClientCreateRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Actions\ClientActions\DeleteClient;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use PragmaRX\Google2FALaravel\Facade as TwoFactor;
use App\Actions\AccountCodeActions\CopyClientAccountCode;
use App\Actions\AccountCodeActions\DeleteClientAccountCode;

class ClientController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.client.index')) {
            return $error;
        }
        $clients = getClientsWithPayment();
        return view('admin.client.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($error = $this->sendPermissionError('admin.client.create')) {
            return $error;
        }
        $professions = Profession::select('id', 'name')->get();
        $services = Service::get();
        return view('admin.client.create', compact('services', 'professions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ClientCreateRequest $request
     * @param AddClient $addClient
     * @param CopyClientAccountCode $copyClientAccountCode
     * @return \Illuminate\Http\Response
     */
    public function store(ClientCreateRequest $request, AddClient $addClient, CopyClientAccountCode $copyClientAccountCode)
    {
        if ($error = $this->sendPermissionError('admin.client.create')) {
            return $error;
        }
        $data = $this->prepareClientDataForCreate($request);
        DB::beginTransaction();

        try {
            $client = $addClient->setData($data)->execute();
            $copyClientAccountCode->setData(['profession_id' => $data['professions'], 'client_id' => $client->id])->execute();
            Alert::success("Success", 'Client Successfully Inserted');
            activity()
                ->performedOn(new Client())
                ->withProperties(['client' => $client->fullname, 'report' => 'New Client Created'])
                ->log('Client > Client List > ' . $client->fullname . ' >  Create ');
            DB::commit();
            return redirect()->route('client.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::error('Oops', 'Something Went Wrong, Please Try Again');
            return back();
        }
    }

    public function prepareClientDataForCreate(ClientCreateRequest $request)
    {
        return [
            'company'           => $request->company,
            'contact_person'    => $request->contact_person,
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'birthday'          => $request->birthday,
            'phone'             => $request->phone,
            'abn_number'        => $request->abn_number,
            'branch'            => $request->branch,
            'tax_file_number'   => $request->tax_file_number,
            'charitable_number' => $request->charitable_number,
            'iran_number'       => $request->iran_number,
            'street_address'    => $request->street_address,
            'state'             => $request->state,
            'post_code'         => $request->post_code,
            'director_name'     => $request->director_name,
            'director_address'  => $request->director_address,
            'agent_name'        => $request->agent_name,
            'agent_address'     => $request->agent_address,
            'agent_number'      => $request->agent_number,
            'agent_abn_number'  => $request->agent_abn_number,
            'auditor_name'      => $request->auditor_name,
            'auditor_address'   => $request->auditor_address,
            'auditor_phone'     => $request->auditor_phone,
            'email'             => $request->email,
            'suburb'            => $request->suburb,
            'country'           => $request->country,
            'gst_method'        => $request->gst_method ?? 0,
            'is_gst_enabled'    => $request->is_gst_enabled,
            'password'          => bcrypt($request->password),
            'services'          => $request->services,
            'professions'       => $request->professions,
            'website'           => $request->website,
        ];
    }

    public function prepareClientDataForUpdate(ClientUpdateRequest $request)
    {
        $data = [
            'company'           => $request->company,
            'contact_person'    => $request->contact_person,
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'birthday'          => $request->birthday,
            'phone'             => $request->phone,
            'abn_number'        => $request->abn_number,
            'branch'            => $request->branch,
            'tax_file_number'   => $request->tax_file_number,
            'charitable_number' => $request->charitable_number,
            'iran_number'       => $request->iran_number,
            'street_address'    => $request->street_address,
            'state'             => $request->state,
            'post_code'         => $request->post_code,
            'director_name'     => $request->director_name,
            'director_address'  => $request->director_address,
            'agent_name'        => $request->agent_name,
            'agent_address'     => $request->agent_address,
            'agent_number'      => $request->agent_number,
            'agent_abn_number'  => $request->agent_abn_number,
            'auditor_name'      => $request->auditor_name,
            'auditor_address'   => $request->auditor_address,
            'auditor_phone'     => $request->auditor_phone,
            'email'             => $request->email,
            'suburb'            => $request->suburb,
            'country'           => $request->country,
            'gst_method'        => $request->gst_method,
            'is_gst_enabled'    => $request->is_gst_enabled ?? 0,
            'password'          => bcrypt($request->password),
            'services'          => $request->services,
            'professions'       => $request->professions,
            'website'           => $request->website,
        ];
        if (empty($request->password)) {
            unset($data['password']);
        }
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.client.edit')) {
            return $error;
        }
        $professions = Profession::select('id', 'name')->get();
        $services    = Service::select('name', 'id')->get();
        return view('admin.client.update', compact('client', 'services', 'professions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ClientUpdateRequest $request
     * @param \App\Client $client
     * @param EditClient $editClient
     * @param CopyClientAccountCode $copyClientAccountCode
     * @param DeleteClientAccountCode $deleteClientAccountCode
     * @return \Illuminate\Http\Response
     */
    public function update(ClientUpdateRequest $request, Client $client, EditClient $editClient, CopyClientAccountCode $copyClientAccountCode, DeleteClientAccountCode $deleteClientAccountCode)
    {
        if ($error = $this->sendPermissionError('admin.client.edit')) {
            return $error;
        }
        $data = $this->prepareClientDataForUpdate($request);
        DB::beginTransaction();
        $previous_professions = $client->professions->pluck('id')->toArray();
        $profession_id_to_delete = array_diff($previous_professions, $data['professions']);
        $profession_id_to_add = array_diff($data['professions'], $previous_professions);

        try {
            $editClient->setInstance($client)->setData($data)->execute();
            $copyClientAccountCode->setData(['profession_id' => $profession_id_to_add, 'client_id' => $client->id])->execute();
            $deleteClientAccountCode->setData($profession_id_to_delete, $client->id)->execute();
            Alert::success('Client Update', 'Client Successfully Updated');
            activity()
                ->performedOn(new Client())
                ->withProperties(['client' => $client->fullname, 'report' => 'Client Data Updated'])
                ->log('Client> Client List > Client > ' . $client->fullname . ' > Update ');
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
            Alert::error('Client Update', $exception->getMessage());
        }
        return redirect()->route('client.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Client $client
     * @param DeleteClient $deleteClient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client, DeleteClient $deleteClient)
    {
        if ($error = $this->sendPermissionError('admin.client.delete')) {
            return $error;
        }
        DB::beginTransaction();
        try {
            $deleteClient->setClient($client)->execute();
            Alert::success('Client Delete', 'Client Successfully Deleted');
            activity()
                ->performedOn(new Client())
                ->withProperties(['client' => $client->fullname, 'report' => 'Client was Deleted'])
                ->log('Client> Client List > Client > ' . $client->fullname . ' > Delete');
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception;
            Alert::error('Client Delete', $exception->getMessage());
        }
        return redirect()->route('client.index');
    }

    //************ Auth *********
    public function showLoginForm()
    {
        if (!Auth::guard('client')->check()) {
            return view('frontend.auth.login');
        }
        return redirect()->route('index');
    }
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:5'],
        ]);
        $client = Client::whereEmail($request->email)->first();
        if ($client && $client->two_factor_secret) {
            $this->validate($request, [
                'code'     => ['required', 'string'],
            ]);
            if (TwoFactor::verifyKey($client->two_factor_secret, $request->code)) {
                if ($this->isUserAuthenticated($request)) {
                    return redirect()->route('index');
                }
            } else {
                return redirect()->back()->withErrors([
                    'unauthenticated' => 'The code you provided is not valid.'
                ])->withInput();
            }
        }
        if ($this->isUserAuthenticated($request)) {
            return redirect()->route('index');
        }
        return redirect()->back()->withErrors([
            'unauthenticated' => 'Email or password is incorrect'
        ])->withInput();
    }

    public function clientRegister(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:clients,email',
            'password'  => 'required|confirmed',
            'agreement' => 'required',
        ]);
        try {
            $data['password'] = bcrypt($data['password']);
            Client::create($data);
            if ($this->isUserAuthenticated($request)) {
                return redirect()->route('index');
            }
            toast('success', 'Registration Success Please make a request!');
        } catch (\Exception $e) {
            toast('error', $e->getMessage());
            return back();
        }
    }

    private function isUserAuthenticated(Request $request)
    {
        $remember = $request->has('remember') ?: false;
        return Auth::guard('client')->attempt([
            'email'    => $request->email,
            'password' => $request->password
            // 'is_active' => 1
        ], $remember);
        // return true;
    }
    public function logout(Request $request)
    {
        $this->guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // Client Impersonation
    public function impersonate(Client $client)
    {
        if ($client) {
            session()->put('impersonate', $client->id);
            activity()
                ->performedOn(new Client())
                ->withProperties(['client' => $client->fullname, 'report' => 'Client profile Impersonate'])
                ->log('Client > Client List > Profile > ' . $client->fullname . ' > Impersonate ');
        }
        return redirect()->route('index');
    }
    public function destroyImpersonate()
    {
        session()->forget('impersonate');
        return redirect()->route('client.index');
    }
}
