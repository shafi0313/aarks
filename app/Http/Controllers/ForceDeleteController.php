<?php

namespace App\Http\Controllers;

use App\Models\PeriodLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use RealRashid\SweetAlert\Facades\Alert;

class ForceDeleteController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.force_delete.index')) {
            return $error;
        }
        return view('admin.force-delete.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.force_delete.index')) {
            return $error;
        }
        $request->validate([
            'password' => 'required|string'
        ]);
        
        // return auth()->user()->password;
        if (Hash::check($request->password, auth()->user()->password)) {       
            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            $affected_rows = 0;
    
            DB::beginTransaction();
            try {
                foreach ($tables as $table) {
                    if (Schema::hasColumn($table, 'deleted_at')) {
                        $affected_rows += DB::table($table)->whereNotNull('deleted_at')->delete();
                    }
                }
                DB::commit();
                Alert::success('Success', "Client data deleted {$affected_rows} rows successfully");
            } catch (\Exception $e) {
                DB::rollback();
                Alert::error('Error', "Client data delete failed");
            }
            // activity()
            //     ->performedOn(new PeriodLock())
            //     ->withProperties(['client' => $client->fullname, 'report' => 'Period Locked'])
            //     ->log('Add/Edit Data > Verify Account > period locked > ' . $client->fullname . ' Locked ');
        } else {
            Alert::error('Password not match! Please Try Again.');
        }
        return back();
    }
}
