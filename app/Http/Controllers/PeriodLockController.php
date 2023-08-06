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
        $clients = getClientsWithPayment();
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
            'date'     => 'required|date_format:d/m/Y',
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
            toast('Password not match!', 'error');
        }
        return back();
    }
}
