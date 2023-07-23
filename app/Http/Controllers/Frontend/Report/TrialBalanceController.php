<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Profession;
use App\Models\Data_storage;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Actions\GeneralLedgerAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Actions\Reports\TrialBalance;
use App\Http\Requests\ShowGeneralLedgerRequest;

class TrialBalanceController extends Controller
{
    public function profession()
    {
        $client = Client::with('professions')->where('id', client()->id)->first();
        return view('frontend.report.trial.select_activity', compact('client'));
    }
    public function report(Request $request, TrialBalance $trial)
    {
        $client     = Client::with('professions')->where('id', $request->client_id)->first();
        $profession = Profession::findOrfail($request->profession_id);

        $data = $trial->report($request, $client, $profession);

        if ($request->submit == 'Print' || $request->submit == 'Email') {
            $pdf = PDF::loadView('frontend.report.trial.print', $data);
            if ($request->submit == 'Print') {
                return $pdf->stream();
            } elseif ($request->submit == 'Email') {
                try {
                    Mail::send('frontend.sales.payment.mail', ['client'=>$client, 'customer'=>$client], function ($mail) use ($pdf, $client) {
                        $mail->to($client->email, $client->email)
                                ->subject('🧾 Trial Balance Generated')
                                ->attachData($pdf->output(), transaction_id(16) . ".pdf");
                    });
                    toast('Trial Balance Mailed Successful!', 'success');
                } catch (\Exception $e) {
                    toast('Opps! Server Side Error!', 'error');
                    return $e->getMessage();
                }
                return redirect()->back();
            }
        }
        return view('frontend.report.trial.report', $data);
    }
}
