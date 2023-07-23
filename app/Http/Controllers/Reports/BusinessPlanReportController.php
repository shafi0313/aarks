<?php

namespace App\Http\Controllers\Reports;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Actions\Reports\BusinessPlanAction;

class BusinessPlanReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:admin.business_plan_report.index']);
    }
    public function index(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.business_plan_report.index')) {
            return $error;
        }
        return view('admin.reports.advance.business-plan.index');
    }
    public function report(Request $request, BusinessPlanAction $plan)
    {
        // return $request;
        $request->validate([
            'year'          => 'required',
            'client_id'     => 'required',
            'profession_id' => 'required',
        ]);
        if ($error = $this->sendPermissionError('admin.business_plan_report.index')) {
            return $error;
        }
        if (!$request->filled('year')) {
            Alert::error('Error', 'Please prepare the business plan first!');
            return back();
        }
        // return array_fill(0, 12, 0);
        // return
        $data = $plan->report($request);
        if ($data['business'] <= 0) {
            Alert::error('Please prepare the business plan first!');
            return back();
        }
        // return ($data['plans'][2])->groupBy(fn ($item) => substr($item->chart_id, -6, 2));
        if ($request->has('print')) {
            // return view('admin.reports.advance.business-plan.print', $data);

            $pdf = PDF::loadView('admin.reports.advance.business-plan.print', $data);
            return $pdf->setPaper('legal', 'landscape')->setWarnings(false)->stream();
        }
        return view('admin.reports.advance.business-plan.report', $data);
    }
}
