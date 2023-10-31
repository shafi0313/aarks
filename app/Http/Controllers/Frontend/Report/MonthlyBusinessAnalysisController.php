<?php

namespace App\Http\Controllers\Frontend\Report;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Actions\DataStore\BusinessPlanAction;

class MonthlyBusinessAnalysisController extends Controller
{
    public function index(Request $request)
    {
        return view('frontend.report.monthly-business-analysis.index');
    }
    public function report(Request $request, BusinessPlanAction $action)
    {
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
        return view('frontend.report.monthly-business-analysis.report', $data, ['months' => $months]);

        Alert::error('Please prepare the business plan first!');
        return back();
        // return view('admin.reports.advance.business-analysis.report', $data);
    }
}
