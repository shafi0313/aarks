<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Period;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class VerifyAccountController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.verify_account.index')) {
            return $error;
        }
        //Permission Will be changed
        $clients = getClientsWithPayment();
        return view('admin.verify_accounts.index', compact('clients'));
    }

    public function selectProfession(Client $client)
    {
        if ($error = $this->sendPermissionError('admin.verify_account.index')) {
            return $error;
        }
        return view('admin.verify_accounts.select_profession', compact('client'));
    }

    public function selectDate(Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.verify_account.index')) {
            return $error;
        }
        return view('admin.verify_accounts.select_date', compact('client', 'profession'));
    }

    public function finalReport(Request $request, Client $client, Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.verify_account.index')) {
            return $error;
        }
        $from_date  = $request->start_date;
        $to_date    = $request->end_date;
        $start_date = makeBackendCompatibleDate($request->start_date)->format('Y-m-d');
        $end_date   = makeBackendCompatibleDate($request->end_date)->format('Y-m-d');

        $ledgerDatum = GeneralLedger::where('client_id', $client->id)
                        ->where('profession_id', $profession->id)
                        ->where('date', '>=', $start_date)
                        ->where('date', '<=', $end_date)
                        ->orderBy('date');
                         // ->where('narration', '!=', 'BST_BANK')
                        // ->where('narration','not like', '%CLEARING')
                        // ->where('narration','not like', '%PAYABLE')

        $data['ledgers'] = $ledgerDatum
                        ->whereNotIn('chart_id', [999998, 999999])
                        ->whereNotIn('source', ['OPN'])
                        ->get(ledgerSetVisible())
                        ->groupBy('transaction_id');

        $data['opns'] = $ledgerDatum->whereSource('OPN')
                            ->get(ledgerSetVisible())
                            ->groupBy('transaction_id');


        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Verify Account Report Show'])
            ->log('Tools > Verify Account Report > Import > ' . $client->fullname . ' > ' . $profession->name . ' Report ');
        return view('admin.verify_accounts.final_report', $data, compact('client', 'profession', 'from_date', 'to_date'));
    }
    
    public function tranView($tran_id, $source)
    {
        if ($error = $this->sendPermissionError('admin.verify_account.index')) {
            return $error;
        }

        $ledgers = GeneralLedger::with('client_account_code')
                    ->where('transaction_id', $tran_id)
                    // ->where('source', $source)
                    ->whereNotIn('chart_id', [999998, 999999])
                    // ->where('narration', '!=', 'BST_BANK')
                    // ->where('narration','not like', '%CLEARING')
                    // ->where('narration','not like', '%PAYABLE')
                    ->orderBy('chart_id')
                    ->get();
        return view('admin.verify_accounts.tran_view', compact('ledgers'));
    }
}
