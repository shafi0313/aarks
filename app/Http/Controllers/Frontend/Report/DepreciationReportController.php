<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use App\Models\Period;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Profession;
use App\Models\DepAssetName;
use App\Models\Depreciation;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Http\Controllers\Controller;

class DepreciationReportController extends Controller
{

    public function index()
    {
        $client = Client::with('professions')->findOrFail(client()->id);
        return view('frontend.report.depreciation.activity', compact('client'));
    }
    public function date(Client $client, Profession $profession)
    {
        $periods = Period::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('year')
            ->groupBy('year')
            ->get();
        return view('frontend.report.depreciation.year', compact(['client', 'profession', 'periods']));
    }
    public function report(Request $request, Client $client, Profession $profession, $year)
    {
        $depAssetNames =  DepAssetName::with('depreciation')
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('year', $year)
            ->get();
        $oldAssetNames =  DepAssetName::with('depreciation')
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('year', '<', $year)
            ->get();
        $depreciations = Depreciation::with(['asset_names' => function ($q) use ($year) {
            $q->where('year', $year);
        }])
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->get();
        if ($request->has('print') && $request->print == true) {
            $pdf = PDF::loadView('admin.reports.depreciation_report.print', compact('depAssetNames', 'year', 'depreciations', 'oldAssetNames', 'client', 'profession'));
            return $pdf->setPaper('legal', 'landscape')->setWarnings(false)->stream();
        }

        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Depreciation Report'])
            ->log('Report > Depreciation Report > ' . $client->fullname . ' > ' . $profession->name);

        return view('frontend.report.depreciation.report', compact(['client', 'profession', 'depAssetNames', 'year', 'depreciations', 'oldAssetNames']));
    }
}
