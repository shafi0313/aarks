<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function withSuccessNotification()
    {
        Session::flash('');
    }

    protected function sendPermissionError(
        $permission,
        $error_message = 'Don\'t have permission to perform this action',
        $error_title = 'Permission Denied'
    ) {
        // dd(auth()->user()->can($permission), $permission);
        if (!auth()->user()->can($permission)) {
            Alert::error($error_title, $error_message);
            return redirect()->back()->withInput();
        }
    }
}
