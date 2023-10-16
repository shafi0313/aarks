<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Actions\Reports\TrialBalance;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class TrialBalanceController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.trial_balance.index')) {
            return $error;
        }

        $clients = getClientsWithPayment();
        return view('admin.reports.trial_balance.index', compact('clients'));
    }

    public function showProfessions(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.trial_balance.index')) {
            return $error;
        }
        return view('admin.reports.trial_balance.select_profession', compact('client'));
    }

    public function selectDate(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.trial_balance.index')) {
            return $error;
        }
        return view('admin.reports.trial_balance.select_date', compact('client', 'profession'));
    }

    public function report(Client $client, Profession $profession, Request $request, TrialBalance $trial)
    {
        if ($error = $this->sendPermissionError('admin.trial_balance.index')) {
            return $error;
        }
        // return
        $data = $trial->report($request, $client, $profession);

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
                    toast('Trial Balance(S/Activity) Mailed Successful!', 'success');
                } catch (\Exception $e) {
                    toast('Opps! Server Side Error!', 'error');
                    return $e->getMessage();
                }
                return redirect()->back();
            }
        }
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Trial Balance(S/Activity) Report'])
            ->log('Report > Trial Balance(S/Activity) Report > '.$client->fullname .' > '. $profession->name);
        return view('admin.reports.trial_balance.report', $data);
    }
}
