<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Actions\Reports\GstPeriodic;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class PeriodicAccruedReportController extends Controller
{
    public function profession()
    {        
        $client = Client::with('professions')->findOrFail(client()->id);
        if ($client->gst_method == 2) {
            return view('frontend.report.periodic_accrued.profession', compact('client'));
        } else {
            Alert::error('Check your GST method', 'You must check GST method because you are in cash');
            return back();
        }
    }
    public function date(Profession $profession)
    {        
        $periods = Period::where('client_id', client()->id)
            ->where('profession_id', $profession->id)
            ->get()->groupBy('year');
        
        return view('frontend.report.periodic_accrued.date', compact(['profession', 'periods']));
    }
    public function report(Request $request, Client $client, Profession $profession, GstPeriodic $periodic)
    {
        
        if ($request->peroid_id) {
            // return
            $data = $periodic->acrued($request);
            return view('frontend.report.periodic_accrued.report', $data);
        } else {
            Alert::warning('You must select Period', 'Select at lest one period!');
            return back();
        }
    }
}
