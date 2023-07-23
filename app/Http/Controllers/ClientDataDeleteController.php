<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use RealRashid\SweetAlert\Facades\Alert;

class ClientDataDeleteController extends Controller
{
    public function password()
    {
        if ($error = $this->sendPermissionError('admin.client_data_delete.index')) {
            return $error;
        }
        session()->forget('password');
        return view('admin.client.data-delete.password');
    }

    public function checkPassword(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.client_data_delete.index')) {
            return $error;
        }
        $request->validate([
            'password' => 'required|string',
        ]);
        session()->put('password', $request->password);
        $admin = Admin::find(1);
        if (Hash::check($request->password, $admin->password)) {
            return redirect()->route('client.data.client');
        }
        Alert::error('Your password is incorrect');
        return back();
    }

    public function client()
    {
        if ($error = $this->sendPermissionError('admin.client_data_delete.index')) {
            return $error;
        }
        $password = session()->get('password');
        $admin    = Admin::find(1);
        if ($password && Hash::check($password, $admin->password)) {
            $clients = Client::get(['id','company','first_name','last_name','email','phone']);
            return view('admin.client.data-delete.client', compact('clients'));
        }
        return redirect()->route('client.data.password');        
    }

    public function destroy(Request $request, Client $client)
    {
        if ($error = $this->sendPermissionError('admin.client_data_delete.index')) {
            return $error;
        }
        $client_id = $client->id; // replace with the actual client ID
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        $affected_rows = 0;

        DB::beginTransaction();
        try {
            foreach ($tables as $table) {
                if (Schema::hasColumn($table, 'client_id')) {
                    $affected_rows += DB::table($table)->where('client_id', $client_id)->delete();
                }
            }
            DB::commit();
            Alert::success('Success', "Client data deleted {$affected_rows} rows successfully");
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', "Client data delete failed");
            // throw $e;
        }
        return redirect()->route('client.data.index');
    }
}
