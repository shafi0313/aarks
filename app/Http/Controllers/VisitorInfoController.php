<?php

namespace App\Http\Controllers;

use App\Models\VisitorInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class VisitorInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visitors = VisitorInfo::paginate(100);
        return view('admin.visitor_info.index', compact('visitors'));
    }

    public function delSelected(Request $request)
    {
        try {
            VisitorInfo::whereIn('id', $request->id)->delete();
            Alert::success('Visitor Information Deleted');
        } catch (\Exception $e) {
            Alert::error('Oops server error');
            #return $e->getMessage();
        }
        return back();
    }
    public function destroy(Request $request)
    {
        try {
            DB::table('visitor_infos')->delete();
            Alert::success('All Visitor Information Deleted');
        } catch (\Exception $e) {
            Alert::error('Oops server error');
            #return $e->getMessage();
        }
        return back();
    }
}
