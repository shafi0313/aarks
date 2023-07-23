<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\PeriodLock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PeriodLockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::leftJoin('client_payment_lists', 'clients.id', '=', 'client_payment_lists.client_id')
            ->select('clients.id','clients.company', 'clients.first_name','clients.last_name','clients.email','clients.phone',
                    'client_payment_lists.status', 'client_payment_lists.is_expire', 'client_payment_lists.status')
            ->orderBy('client_payment_lists.status', 'desc')
            ->orderBy('client_payment_lists.is_expire', 'desc')
            ->get();
        return view('admin.period_lock.index', compact('clients'));
    }
    public function client(Client $client)
    {
        $lock = PeriodLock::where('client_id', $client->id)->first();
        return view('admin.period_lock.set_period_lock', compact('client', 'lock'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:d/m/Y',
            'password' => 'required|string'
        ]);
        $client = Client::find($request->client_id);
        $data = [
            'client_id' => $request->client_id,
            'date'      => makeBackendCompatibleDate($request->date),
            'lock_by'   => auth()->id(),
        ];
        // return auth()->user()->password;
        if (Hash::check($request->password, auth()->user()->password)) {
            $lock = PeriodLock::where('client_id', $request->client_id)->first();
            if ($lock) {
                $lock->update($data);
            } else {
                PeriodLock::create($data);
            }
            activity()
                ->performedOn(new PeriodLock())
                ->withProperties(['client' => $client->fullname, 'report' => 'Period Locked'])
                ->log('Add/Edit Data > Verify Account > period locked > ' . $client->fullname . ' Locked ');
            toast('Period Locked Success!', 'success');
        } else {
            toast('password not match!', 'error');
        }
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PeriodLock  $periodLock
     * @return \Illuminate\Http\Response
     */
    public function edit(PeriodLock $periodLock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PeriodLock  $periodLock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PeriodLock $periodLock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PeriodLock  $periodLock
     * @return \Illuminate\Http\Response
     */
    public function destroy(PeriodLock $periodLock)
    {
        //
    }
}
