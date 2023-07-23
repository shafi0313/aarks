<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Actions\Reports\TrialBalance;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ConsoleTrialBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.cons_trial_balance.index')) {
            return $error;
        }
        //Permission Will be changed
        $clients = Client::all();
        return view('admin.reports.trial_balance.console.index', compact('clients'));
    }

    public function date(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.cons_trial_balance.index')) {
            return $error;
        }
        return view('admin.reports.trial_balance.console.select_date', compact('client'));
    }

    public function report(Client $client, Request $request, TrialBalance $trial)
    {
        if ($error = $this->sendPermissionError('admin.cons_trial_balance.index')) {
            return $error;
        }
        $data = $trial->consoleReport($request, $client);

        if ($request->print || $request->email) {
            $pdf = PDF::loadView('frontend.report.trial.print', $data);
            if ($request->print) {
                return $pdf->stream();
            } elseif ($request->email) {
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
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'report' => 'Console Trial Balance Report'])
            ->log('Report > Console Trial Balance Report > '.$client->fullname);
        return view('admin.reports.trial_balance.console.report', $data);
    }
}
