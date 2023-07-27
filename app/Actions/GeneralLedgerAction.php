<?php

namespace App\Actions;

use App\Models\Client;
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
use App\Models\Frontend\DedotrPaymentReceive;
use App\Models\Frontend\CreditorPaymentReceive;

class GeneralLedgerAction
{
    public static function show(Request $request, Client $client, $start_date, $end_date)
    {
        if ($start_date->format('m') >= 07 & $start_date->format('m') <= 12) {
            $start_year = $start_date->format('Y') . '-07-01';
            $end_year   = $start_date->format('Y') + 1 . '-06-30';
        } else {
            $start_year = $start_date->format('Y') - 1 . '-07-01';
            $end_year   = $start_date->format('Y')  . '-06-30';
        }
        $format_start_date = $start_date->format('Y-m-d');

        $client_account_codes = ClientAccountCode::with([
            'generalLedger' => fn ($q) => 
                $q->select(ledgerSetVisible())
                ->where('date', '>=', $start_date)->where('date', '<=', $end_date)
            ])
            ->where('client_id', $client->id)
            ->where(function ($q) {
                $q->where('code', 'like', '5%')
                    ->orWhere('code', 'like', '9%');
            })
            ->where('code', '!=', 999999)
            ->orderBy('code', 'asc')
            ->groupBy('code')
            ->get(['id', 'code', 'name', 'client_id']);

        $inExPreData = GeneralLedger::where('client_id', $client->id)
            // ->where('profession_id', $request->profession_id)
            ->where('date', '>=', $start_year)
            ->where('date', '<', $format_start_date)
            ->where(function ($q) {
                $q->where('chart_id', 'like', '1%')
                    ->orWhere('chart_id', 'like', '2%');
            })
            ->select('*', DB::raw('sum(credit) as credit, sum(debit) as debit, sum(balance) as balance, sum(gst) as gst'))
            ->groupBy('chart_id')->orderBy('chart_id')
            ->get();

        $assetLailaPreData = GeneralLedger::where('client_id', $client->id)
            // ->where('profession_id', $request->profession_id)
            ->where('date', '>=', $start_year)
            ->where('date', '<', $format_start_date)
            ->where(function ($q) {
                $q->where('chart_id', 'like', '5%')
                    ->orWhere('chart_id', 'like', '9%');
            })
            // ->whereNotIn('chart_id', [954100,999999,912100,912101])
            ->select('*', DB::raw('sum(credit) as credit, sum(debit) as debit, sum(balance) as balance, sum(gst) as gst'))
            ->groupBy('chart_id')->orderBy('chart_id')
            ->get();

        $preAssLilas = GeneralLedger::where('client_id', $client->id)
            // ->where('profession_id', $request->profession_id)
            // ->where('date', '<', $start_year) //If he what to change it to financial Start date
            ->where('date', '<=', $format_start_date)
            ->select('*', DB::raw("sum(balance) as OpenBl"))
            ->where(function ($q) {
                $q->where('chart_id', 'like', '5%')
                    ->orWhere('chart_id', 'like', '9%');
            })
            ->groupBy('chart_id')
            ->orderBy('chart_id')
            ->get();

        $ledgers = GeneralLedger::with('client_account_code')->whereClientId($client->id)
            ->where('date', '>=', $format_start_date)
            ->where('date', '<=', $end_date->format('Y-m-d'))
            ->where(function ($q) {
                $q->where('chart_id', 'like', '1%')
                    ->orWhere('chart_id', 'like', '2%');
            })
            ->whereBetween('chart_id', [$request->from_account, $request->to_account])
            ->where('source', '!=', 'OPN')
            // ->select('*', DB::raw('sum(credit) as credit, sum(debit) as debit, sum(balance) as balance, sum(gst) as gst'))
            ->get()->groupBy('chart_id')->sortBy('chart_id');

        $open_balances = GeneralLedger::whereClientId($client->id)
            ->where('date', '<', $format_start_date)
            ->where(fn ($q) => $q->where('chart_id', 'like', '5%')
            ->orWhere('chart_id', 'like', '9%'))
            ->whereBetween('chart_id', [$request->from_account, $request->to_account])
            ->where('source', '!=', 'OPN')
            ->selectRaw("chart_id,sum(balance) as openBl")
            ->groupBy('chart_id')->get()->sortBy('chart_id');

        $retains = GeneralLedger::where('client_id', $client->id)
            // ->where('profession_id', $request->profession_id)
            ->where('date', '<', $start_year)
            ->where('chart_id', 999999)
            ->get();
        return compact('start_date', 'end_date', 'client_account_codes', 'client', 'inExPreData', 'assetLailaPreData', 'retains', 'preAssLilas', 'ledgers', 'open_balances');
    }

    public static function showTran($transaction_id, $source)
    {
        $generalLedger = GeneralLedger::select('*', DB::raw("sum(balance) as tranBlnc"))
            ->with('client_account_code')
            ->where('transaction_id', $transaction_id)
            ->where('chart_id', '!=', 999999)
            ->where('chart_id', '!=', 999998);
        if ($source == "ADT") {
            $transactions = $generalLedger->where('chart_id', '!=', 954100)
                ->where('source', 'ADT')
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
        } elseif ($source == "BST") {
            $transactions = $generalLedger->where('narration', '!=', 'BST_BANK')
                // ->where('chart_id', '!=', 954100) //Uncomment if not match
                // ->where('chart_id', 'not like', '551%')
                ->where('source', 'BST')
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
        } elseif ($source == "INP") {
            $transactions = $generalLedger->where('narration', '!=', 'INP_BANK')
                ->where('source', 'INP')
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
        } elseif ($source == "JNP") {
            $transactions = $generalLedger
                // ->where('chart_id', '!=', 954100)
                // ->where('chart_id', 'not like', '551%')
                // ->where('narration', '!=', 'JNP_BANK')
                ->where('source', 'JNP')
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
        } elseif ($source == "DEP") {
            $transactions = $generalLedger->where('chart_id', '!=', 954100)
                ->where('source', 'DEP')
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
        } elseif ($source == "PAY") {
            $transactions = $generalLedger->where('source', 'PAY')
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
        } elseif ($source == "INV" || $source == "PIN") {
            $transactions = $generalLedger->where(function ($q) {
                $q->where('source', 'PIN')
                    ->orWhere('source', 'INV');
            })
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
        } elseif ($source == "PBP" || $source == "PBN") {
            $transactions = $generalLedger->where(function ($q) {
                $q->where('source', 'PBP')
                    ->orWhere('source', 'PBN');
            })
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
        } elseif ($source == "CBE") {
            $transactions = $generalLedger->where('source', 'CBE')
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
        } elseif ($source == "OPN") {
            $transactions = $generalLedger->where('source', 'OPN')
                ->groupBy('chart_id')
                ->orderBy('chart_id')
                ->get();
        }
        if ($source == "INP" || $source == "BST") {
            $bank = GeneralLedger::with('client_account_code')
                ->where('transaction_id', $transaction_id)
                ->whereNotIn('chart_id', $transactions->pluck('chart_id')->toArray())
                ->where('chart_id', 'like', '551%')
                ->first();
        } else {
            $bank = GeneralLedger::with('client_account_code')
                ->where('transaction_id', $transaction_id)
                ->where('chart_id', 'like', '551%')
                ->first();
        }
        $loan = GeneralLedger::where('transaction_id', $transaction_id)
            ->where('chart_id', 954100)
            ->first();
        return  compact(['transactions', 'loan', 'bank', 'source']);
    }
    public static function transactionsBySource($client, $chart, $tran_id, $src = 'ADT')
    {
        // Source: ADT, INV, PIN, PBP, PBN, CBE, OPN, BST, INP, JNP, DEP, RIV
        switch ($src) {
            case 'ADT':
                $transactions = Data_storage::whereClientId($client->id)->where('chart_id', $chart->code)->where('trn_id', $tran_id)->get();
                break;
            case 'INV':
                $transactions = Dedotr::with(['clientAccountCode' => fn ($q) => $q->whereClientId($client->id), 'customer'])->whereClientId($client->id)->whereChartId($chart->code)->whereTranId($tran_id)->get();
                break;
            case 'PIN':
                $transactions = DedotrPaymentReceive::with(['clientAccountCode' => fn ($q) => $q->whereClientId($client->id), 'customer'])->whereClientId($client->id)->whereChartId($chart->code)->whereTranId($tran_id)->get();
                break;
            case 'PBP':
                $transactions = Creditor::with(['clientAccountCode' => fn ($q) => $q->whereClientId($client->id), 'customer'])->whereClientId($client->id)->whereChartId($chart->code)->whereTranId($tran_id)->get();
                break;
            case 'PBN':
                $transactions = CreditorPaymentReceive::with(['clientAccountCode' => fn ($q) => $q->whereClientId($client->id), 'customer'])->whereClientId($client->id)->whereChartId($chart->code)->whereTranId($tran_id)->get();
                break;
            case 'BST':
                $transactions = BankStatementImport::with(['client_account_code'])->whereClientId($client->id)->whereAccountCode($chart->id)->whereTranId($tran_id)->get();
                break;
            case 'INP':
                $transactions = BankStatementInput::with(['client_account_code'])->whereClientId($client->id)->whereAccountCode($chart->id)->whereTranId($tran_id)->get();
                break;
            case 'JNP':
                $transactions = JournalEntry::with(['client_account_code'])->whereClientId($client->id)->whereAccountCode($chart->id)->whereTranId($tran_id)->get();
                break;
                // case 'DEP':
                //     $transactions = JournalEntry::with(['client_account_code'])->whereClientId($client->id)->whereAccountCode($chart->id)->whereTranId($tran_id)->get();
                //     break;
            case 'OPN':
                $transactions = GeneralLedger::with(['clientAccountCode' => fn ($q) => $q->whereClientId($client->id)])
                    ->whereClientId($client->id)->whereAccountCode($chart->id)
                    ->whereSource('OPN')->whereTransactionId($tran_id)->get();
                break;
            case 'RIV':
                $transactions = Dedotr::with(['clientAccountCode' => fn ($q) => $q->whereClientId($client->id), 'customer'])->whereClientId($client->id)->whereChartId($chart->code)->whereTranId($tran_id)->get();
                break;
            case 'CBE':
                $transactions = CashBook::with(['accountCode' => fn ($q) => $q->whereClientId($client->id)])->whereClientId($client->id)->whereChartId($chart->code)->whereTranId($tran_id)->get();
                break;
            default:
                $transactions = [];
                break;
        }
        return $transactions;
    }
}
