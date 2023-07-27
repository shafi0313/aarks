<?php

namespace App\Http\Controllers\Reports;

use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use App\Actions\GeneralLedgerAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ShowGeneralLedgerRequest;

class GeneralLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.general_ledger.index')) {
            return $error;
        }
        $clients = getClientsWithPayment();
        return view('admin.reports.general_ledger.index', compact('clients'));
    }


    public function profession(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.general_ledger.index')) {
            return $error;
        }
        return view('admin.reports.general_ledger.select_profession', compact('client'));
    }

    public function date(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.general_ledger.index')) {
            return $error;
        }
        $client_account_codes = ClientAccountCode::where('client_id', $client->id)
                            // ->where('profession_id', $profession->id)
                            ->where('code', '!=', 999999)
                            ->orderBy('code', 'asc')->groupBy('code')->get();
        return view('admin.reports.general_ledger.date', compact('client', 'client_account_codes'));
    }
    public function report(ShowGeneralLedgerRequest $request, GeneralLedgerAction $action)
    {
        if ($error = $this->sendPermissionError('admin.general_ledger.index')) {
            return $error;
        }
        $start_date = makeBackendCompatibleDate($request->start_date);
        $end_date   = makeBackendCompatibleDate($request->end_date);
        $client     = Client::findOrFail($request->client_id);
        // $profession = Profession::findOrFail($request->profession_id);
        // return
        $data       = $action->show($request, $client, $start_date, $end_date);
        // return $data['open_balances'];
        // $data['profession']   = $profession;
        $data['from_account'] = $request->from_account;
        $data['to_account']   = $request->to_account;
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, /* 'profession' => $profession->name, */ 'report' => 'General Ledger Report'])
            ->log('Report > General Ledger Report > '.$client->fullname /* .' > '. $profession->name */);
        return view('admin.reports.general_ledger.report', $data);
    }

    public function showTransaction($transaction_id, $source, GeneralLedgerAction $action)
    {
        if ($error = $this->sendPermissionError('admin.general_ledger.index')) {
            return $error;
        }
        $data = $action->showTran($transaction_id, $source);
        return view('admin.reports.general_ledger.show_transaction', $data);
    }
    public function showDataStoreTransaction($trn_id, $code, $src = 'ADT')
    {
        if ($error = $this->sendPermissionError('admin.general_ledger.index')) {
            return $error;
        }
        $client = GeneralLedger::whereTransactionId($trn_id)->first()->client;
        $chart = ClientAccountCode::where('code', $code)->whereClientId($client->id)->first();
        $transactions = GeneralLedgerAction::transactionsBySource($client, $chart, $trn_id, $src);

        return view('admin.reports.general_ledger.raw-data-transaction', compact('transactions', 'src'));
    }
    public function print(ShowGeneralLedgerRequest $request, GeneralLedgerAction $action)
    {
        if ($error = $this->sendPermissionError('admin.general_ledger.index')) {
            return $error;
        }
        // return $request;
        $start_date = makeBackendCompatibleDate($request->start_date);
        $end_date   = makeBackendCompatibleDate($request->end_date);
        $client     = Client::findOrFail($request->client_id);
        // $profession = Profession::findOrFail($request->profession_id);
        $data       = $action->show($request, $client, $start_date, $end_date);

        // $data['profession']   = $profession;
        $data['from_account'] = $request->from_account;
        $data['to_account']   = $request->to_account;

        $pdf = PDF::loadView('frontend.report.ledger.print', $data);
        if ($request->submit == 'Print') {
            return $pdf->stream();
        } elseif ($request->submit == 'Email') {
            try {
                Mail::send('frontend.sales.payment.mail', ['client'=>$client, 'customer'=>$client], function ($mail) use ($pdf, $client) {
                    $mail->to($client->email, $client->email)
                        ->subject('🧾 General Ledger Generated')
                        ->attachData($pdf->output(), transaction_id(16) . ".pdf");
                });
                toast('Ledger Mailed Successful!', 'success');
            } catch (\Exception $e) {
                toast('Opps! Server Side Error!', 'error');
                return $e->getMessage();
            }
            return redirect()->back();
        }
        // return view('admin.reports.general_ledger.report', $data);
        return view('frontend.report.ledger.report', $data);
    }
}
