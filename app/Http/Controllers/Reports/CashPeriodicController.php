<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Actions\Reports\GstPeriodic;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class CashPeriodicController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.periodic_cash.index')) {
            return $error;
        }

        $clients = Client::leftJoin('client_payment_lists', 'clients.id', '=', 'client_payment_lists.client_id')
            ->select('clients.id','clients.company', 'clients.first_name','clients.last_name','clients.email','clients.phone',
                    'client_payment_lists.status', 'client_payment_lists.is_expire', 'client_payment_lists.status')
            ->orderBy('client_payment_lists.status', 'desc')
            ->orderBy('client_payment_lists.is_expire', 'desc')
            ->get();
        return view('admin.reports.cash_periodic.index', compact('clients'));
    }
    public function profession(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.periodic_cash.index')) {
            return $error;
        }
        if ($client->gst_method == 1) {
            return view('admin.reports.cash_periodic.profession', compact('client'));
        } else {
            Alert::error('Check your GST method', 'You must check GST method because you are in Accrued');
            return back();
        }
    }
    public function date(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.periodic_cash.index')) {
            return $error;
        }
        $periods = Period::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->get()->groupBy('year');
        return view('admin.reports.cash_periodic.date', compact(['client', 'profession', 'periods']));
    }
    public function report(Request $request, Client $client, Profession $profession, GstPeriodic $periodic)
    {
        // return $request;
        if ($error = $this->sendPermissionError('admin.periodic_cash.index')) {
            return $error;
        }
        if ($request->peroid_id) {
            // return
            $data = $periodic->cash($request);
            return view('admin.reports.cash_periodic.report', $data);
        } else {
            Alert::warning('You must select Period', 'Select at lest one period!');
            return back();
        }
    }
}
