<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use App\Models\DepAssetName;
use App\Models\Depreciation;
use App\Models\GeneralLedger;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class DepreciationReportController extends Controller
{

    public function index()
    {
        if ($error = $this->sendPermissionError('admin.depreciation_report.index')) {
            return $error;
        }

        $clients = getClientsWithPayment();
        return view('admin.reports.depreciation_report.index', compact('clients'));
    }
    public function profession($id)
    {
        if ($error = $this->sendPermissionError('admin.depreciation_report.index')) {
            return $error;
        }
        $client = Client::with('professions')->findOrFail($id);
        return view('admin.reports.depreciation_report.select_profession', compact('client'));
    }
    public function date(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.depreciation_report.index')) {
            return $error;
        }
        $periods = Period::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->orderBy('year')
            ->groupBy('year')
            ->get();
        return view('admin.reports.depreciation_report.financial_year', compact(['client', 'profession', 'periods']));
    }
    public function report(Client $client, Profession $profession, $year)
    {
        if ($error = $this->sendPermissionError('admin.depreciation_report.index')) {
            return $error;
        }
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

        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Depreciation Report'])
            ->log('Report > Depreciation Report > ' . $client->fullname . ' > ' . $profession->name);

        return view('admin.reports.depreciation_report.final_report', compact(['client', 'profession', 'depAssetNames', 'year', 'depreciations', 'oldAssetNames']));
    }

    public function print(Client $client, Profession $profession, $year)
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
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'report' => 'Depriciation Report'])
            ->log('Report > Depriciation Report > ' . $client->fullname);

        // return view('admin.reports.depreciation_report.print', compact('depAssetNames', 'year', 'depreciations', 'oldAssetNames', 'client', 'profession'));
        $pdf = PDF::loadView('admin.reports.depreciation_report.print', compact('depAssetNames', 'year', 'depreciations', 'oldAssetNames', 'client', 'profession'));
        return $pdf->setPaper('legal', 'landscape')->setWarnings(false)->stream();
    }
}
