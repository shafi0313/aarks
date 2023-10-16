<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use Illuminate\Support\Facades\DB;
use App\Actions\Reports\GstPeriodic;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class AccruedPeriodicController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.periodic_accrued.index')) {
            return $error;
        }

        $clients = getClientsWithPayment();
        return view('admin.reports.accrued_periodic.index', compact('clients'));
    }

    public function profession(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.periodic_accrued.index')) {
            return $error;
        }
        if ($client->gst_method == 2) {
            return view('admin.reports.accrued_periodic.profession', compact('client'));
        } else {
            Alert::error('Check your GST method', 'You must check GST method because you are in Cash');
            return back();
        }
    }

    public function date(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.periodic_accrued.index')) {
            return $error;
        }
        $periods = Period::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->get()->groupBy('year');
        return view('admin.reports.accrued_periodic.date', compact(['client', 'profession', 'periods']));
    }

    public function report(Request $request, GstPeriodic $periodic)
    {
        // return $request;
        if ($error = $this->sendPermissionError('admin.periodic_accrued.index')) {
            return $error;
        }
        if ($request->peroid_id) {
            $data = $periodic->acrued($request);
            $client     = Client::findOrFail($request->client_id);
            activity()
                ->performedOn(new GeneralLedger())
                ->withProperties(['client' => $client->fullname, 'report' => 'Console Balance Sheet Report'])
                ->log('Report > Console Balance Sheet Report ');
            return view('admin.reports.accrued_periodic.report', $data);
        } else {
            Alert::warning('You must select Period', 'Select at lest one period!');
            return back();
        }
    }
}
