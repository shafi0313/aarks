<?php

namespace App\Http\Controllers\Admin;

use App\Models\LoggingInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class LoggingInfoController extends Controller
{
    public function index(Request $request)
    {
        // if ($error = $this->authorize('logging-info-manage')) {
        //     return $error;
        // }
        // return$loggingInfos = LoggingInfo::all();
        if ($request->ajax()) {
            $loggingInfos = LoggingInfo::with(['clientUser', 'adminUser'])->latest();
            return DataTables::of($loggingInfos)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->diffForHumans();
                })
                ->addColumn('login_at', function ($row) {
                    return Carbon::parse($row->login_at)->format('d/m/Y H:i A');
                })
                ->addColumn('attempt', function ($row) {
                    return $row->attempt == 1 ? 'Success' : 'Failed';
                })
                ->addColumn('system_locked', function ($row) {
                    return $row->system_locked == 1 ? 'Yes' : 'No';
                })
                ->addColumn('mfa', function ($row) {
                    return $row->mfa == 1 ? 'Yes' : 'No';
                })
                ->addColumn('logout_at', function ($row) {
                    return $row->logout_at ? 'Yes' : 'No';
                })
                ->addColumn('duration', function ($row) {
                    $loginAt = Carbon::parse($row->login_at);
                    $logoutAt = Carbon::parse($row->logout_at);
                    return $loginAt->diffForHumans($logoutAt, true);
                })
                // ->addColumn('action', function ($row) {
                //     $btn = '';
                //     if (userCan('slider-edit')) {
                //         $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.sliders.edit', $row->id), 'row' => $row]);
                //     }
                //     if (userCan('slider-delete')) {
                //         $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.sliders.destroy', $row->id), 'row' => $row, 'src' => 'dt']);
                //     }
                //     return $btn;
                // })
                ->rawColumns(['login_at'])
                ->make(true);
        }
        return view('admin.logging_audit.index');
    }
}