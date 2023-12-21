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
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '<a href="' . route('logging-infos.show', $row->id) . '" class="btn btn-sm btn-info" title="View">
                            <i class="fa fa-eye"></i>
                        </a>';
                    return $btn;
                })
                ->rawColumns(['login_at', 'action'])
                ->make(true);
        }
        return view('admin.logging_audit.index');
    }

    public function show(Request $request, $id)
    {
        // if ($error = $this->authorize('logging-info-manage')) {
        //     return $error;
        // }
        $loggingInfos = LoggingInfo::with('activities')->findOrFail($id)->activities()->paginate(40);
        return view('admin.logging_audit.show', compact('loggingInfos'));
    }
}
