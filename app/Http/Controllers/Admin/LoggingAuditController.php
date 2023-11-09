<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoggingAuditController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return view('admin.logging_audit.index', compact('admins'));
    }
}
