<?php

namespace App\Http\Controllers\Reports;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Actions\DataStore\BusinessPlanAction;

class MonthlyBusinessAnalysisController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:admin.business_analysis_report.index']);
    }

    public function index(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.business_analysis_report.index')) {
            return $error;
        }
        return view('admin.reports.advance.business-analysis.index');
    }
    public function report(Request $request, BusinessPlanAction $action)
    {
        if ($error = $this->sendPermissionError('admin.business_analysis_report.index')) {
            return $error;
        }
        $request->validate([
            'year'          => 'required',
            'client_id'     => 'required',
            'profession_id' => 'required',
        ]);
        if (!$request->filled('year')) {
            Alert::error('Error', 'Please prepare the business plan first!');
            return back();
        }

        $date       = Carbon::parse('30-Jun-'. $request->year);
        $client     = Client::findOrFail($request->client_id);
        $profession = Profession::findOrFail($request->profession_id);

        $lastYear = makeBackendCompatibleDate('01/06/'.$request->year-1);
        $months = [];
        foreach (range(1, 12) as $month) {
            $lastYear->addMonth();
            $months[(int) $lastYear->format('m')] = $lastYear->format('F') .' '. $lastYear->format('y');
        }
        // return
        $data       = $action->details($request, $client, $profession);
        if ($request->has('print')) {
            // return view('admin.reports.advance.business-analysis.print', $data, ['months' => $months]);

            $pdf = PDF::loadView('admin.reports.advance.business-analysis.print', $data, ['months' => $months]);
            return $pdf->setPaper('legal', 'landscape')->setWarnings(false)->stream();
        }
        return view('admin.reports.advance.business-analysis.report', $data, ['months' => $months]);

        Alert::error('Please prepare the business plan first!');
        return back();
        // return view('admin.reports.advance.business-analysis.report', $data);
    }
}
