<?php

namespace App\Http\Controllers\Frontend;

use App\Models\PeriodLock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class PeriodLockController extends Controller
{
    public function index()
    {
        $lock = PeriodLock::where('client_id', client()->id)->first();
        return view('frontend.period_lock.index', compact('lock'));
    }

    public function store(Request $request)
    {        
        $request->validate([
            'date'     => 'required|date_format:d/m/Y',
            'password' => 'required|string'
        ]);
        
        $data = [
            'client_id' => client()->id,
            'date'      => makeBackendCompatibleDate($request->date),
            'lock_by'   => client()->id,
        ];
        
        if (Hash::check($request->password, client()->password)) {
            $lock = PeriodLock::where('client_id', client()->id)->first();
            if ($lock) {
                $lock->update($data);
            } else {
                PeriodLock::create($data);
            }
            // activity()
            //     ->performedOn(new PeriodLock())
            //     ->withProperties(['client' => $client->fullname, 'report' => 'Period Locked'])
            //     ->log('Add/Edit Data > Verify Account > period locked > ' . $client->fullname . ' Locked ');
            // toast('Period Locked Success!', 'success');

            Alert::success('Success', 'Period Locked Success!');
        } else {
            Alert::error('Error', 'Password not match!');
        }
        return back();
    }
}
