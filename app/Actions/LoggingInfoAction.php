<?php

namespace App\Actions;

use Browser;
use App\Models\LoggingInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoggingInfoAction extends Controller
{
    public static function login(Request $request)
    {
        if($request->path() == 'client/login'){
            $userType = 'client';
            $userId = Auth::guard('client')->user()->id;
        }else{
            $userType = 'admin';
            $userId = Auth::guard('admin')->user()->id;
        }

        $mfa = $request->code;

        $data = [
            'user_id'       => $userId,
            'user_type'     => $userType,
            'login_at'      => now(),
            'ip_address'    => $request->ip(),
            'area'          => geoip($request->ip())->country,
            'user_agent'    => $request->userAgent(),
            'device'        => Browser::deviceType(),
            'browser'       => Browser::browserName(),
            'os'            => Browser::platformName(),
            'attempt'       => 1,
            'system_locked' => 0,
            'mfa'           => $mfa ? 1 : 0,
            'mfa_code'      => $mfa ? $mfa : '',
        ];
        $userId ? LoggingInfo::create($data): '';
    }

    public static function logout(Request $request)
    {
        if($request->path() == 'client/log-out'){
            $userType = 'client';
            $userId = Auth::guard('client')->user()->id;
        }else{
            $userType = 'admin';
            $userId = Auth::guard('admin')->user()->id;
        }
        if($userId){
            LoggingInfo::whereUserId($userId)->whereUserType($userType)->latest()->first()->update(['logout_at' => now()]);
        }
    }
}
