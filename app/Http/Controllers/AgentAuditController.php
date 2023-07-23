<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class AgentAuditController extends Controller
{
    public function index()
    {
        $agents = Admin::role('Agent')->get(['id','name']);
        return view('admin.audit.agent.index', compact('agents'));
    }
    public function activity(Admin $agent)
    {
        $activities = Activity::latest('created_at')->paginate(40);
        // $activities = $agent->actions->sortByDesc('created_at');
        // $all = Activity::orderByDesc('created_at')->get();
        return view('admin.audit.agent.activity', compact('activities', 'agent'));
    }
    public function delete(Activity $activity)
    {
        try {
            $activity->delete();
            toast('Deleted', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            toast('Oops Error', 'error');
            #return $e->getMessage();
        }
    }
    public function bulkDelete(Request $request)
    {
        try {
            Activity::whereIn('id', $request->id)->delete();
            toast('Agent Audit Deleted', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            toast('Oops Error', 'error');
            #return $e->getMessage();
        }
    }
    public function destroy(Request $request)
    {
        try {
            DB::table('activity_log')->delete();
            toast('Agent Audit Deleted', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            toast('Oops Error', 'error');
            #return $e->getMessage();
        }
    }
}
