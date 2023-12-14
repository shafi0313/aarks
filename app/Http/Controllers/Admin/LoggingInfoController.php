<?php

namespace App\Http\Controllers\Admin;

use App\Models\LoggingInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            $loggingInfos = LoggingInfo::latest();
            return DataTables::of($loggingInfos)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->diffForHumans();
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
                ->rawColumns([])
                ->make(true);
        }
        return view('admin.logging_audit.index');
    }
}
