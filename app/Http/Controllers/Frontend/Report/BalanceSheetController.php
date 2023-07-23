<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\Reports\BalanceSheet;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class BalanceSheetController extends Controller
{
    public function index()
    {
        $client = Client::with('professions')->where('id', client()->id)->first();
        return view('frontend.report.balance.select_activity', compact('client'));
    }
    public function report(Request $request, BalanceSheet $balance)
    {
        $client              = Client::findOrFail($request->client_id);
        $profession          = Profession::findOrFail($request->profession_id);
        $data = $balance->report($request, $client, $profession);
        if ($request->has('print') && $request->print == true) {
            $pdf = PDF::loadView('admin.reports.balance_sheet.print', $data);
            return $pdf->stream('Detailed Balance Sheet.pdf');
        }
        return view('frontend.report.balance.report', $data);
    }
}
