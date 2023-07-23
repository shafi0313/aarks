<?php

namespace App\Http\Controllers\Frontend\Report;

use App\Models\Client;
use App\Models\Profession;
use App\Models\Data_storage;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\Frontend\Dedotr;
use App\Models\ClientAccountCode;
use App\Models\Frontend\CashBook;
use App\Models\Frontend\Creditor;
use App\Models\BankStatementInput;
use Illuminate\Support\Facades\DB;
use App\Models\BankStatementImport;
use App\Actions\GeneralLedgerAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Actions\Reports\TrialBalance;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Frontend\DedotrPaymentReceive;
use App\Http\Requests\ShowGeneralLedgerRequest;
use App\Models\Frontend\CreditorPaymentReceive;

class GeneralLedgerController extends Controller
{
    public function profession()
    {
        $client = Client::with('professions')->where('id', client()->id)->first();
        return view('frontend.report.ledger.select_profession', compact('client'));
    }
    public function date()
    {
        $client = Client::find(client()->id);
        $codes = ClientAccountCode::where('client_id', $client->id)
                // ->where('profession_id', $profession->id)
                ->where('code', '!=', 999999)
                ->orderBy('code')->get();
        return view('frontend.report.ledger.select_activity', compact('client', 'codes'));
    }
    public function report(ShowGeneralLedgerRequest $request, GeneralLedgerAction $action)
    {
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
    public function showTransaction($transaction_id, $source, GeneralLedgerAction $action)
    {
        $data = $action->showTran($transaction_id, $source);
        return view('frontend.report.ledger.show_transaction', $data);
    }
    public function showDataStoreTransaction($trn_id, $code, $src = 'ADT')
    {
        $client  = GeneralLedger::whereTransactionId($trn_id)->first()->client;
        $chart = ClientAccountCode::where('code', $code)->whereClientId($client->id)->first();
        $transactions = GeneralLedgerAction::transactionsBySource($client, $chart, $trn_id, $src);

        return view('frontend.report.ledger.raw-data-transaction', compact('transactions', 'src'));
    }
}
