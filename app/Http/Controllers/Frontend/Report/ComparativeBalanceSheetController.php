<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Reports\ComperativeBalance;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ComparativeBalanceSheetController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->where('id', client()->id)->first();
        return view('frontend.report.comparative-balance.select_activity', compact('client'));
    }
    public function report(Request $request, ComperativeBalance $balance)
    {
        $client              = Client::findOrFail($request->client_id);
        $profession          = Profession::findOrFail($request->profession_id);

        $data = $balance->report($request, $client, $profession);

        if ($request->has('print') && $request->print == true) {
            $pdf = PDF::loadView('admin.reports.comperative_balance_sheet.print', $data);
            return $pdf->stream('Comperative Balance Report.pdf');
        }
        return view('frontend.report.comparative-balance.report', $data);
    }
}
