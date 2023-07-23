<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\Frontend\Dedotr;
use App\Models\Frontend\CashBook;
use App\Models\Frontend\Creditor;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\Job;
use App\Http\Controllers\Controller;
use App\Models\BankStatementImport;
use App\Models\BankStatementInput;
use App\Models\Frontend\CustomerCard;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Frontend\DedotrPaymentReceive;
use App\Models\Frontend\CreditorPaymentReceive;

class TrashActionController extends Controller
{
    protected $method = 'restore';
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->method = request()->method() == "PUT"?"restore":"forceDelete";
    }
    public function index(Request $request, Client $client, $id, $src)
    {
        // return $request;
        $data = '';
        DB::beginTransaction();
        try {
            switch ($src) {
                case 'INV':
                    $data = $this->inv($request, $client, $src, $id);
                    break;
                case 'PIN':
                    $data = $this->pin($request, $client, $src, $id);
                    break;
                case 'PBP':
                    $data = $this->pbp($request, $client, $src, $id);
                    break;
                case 'PBN':
                    $data = $this->pbn($request, $client, $src, $id);
                    break;
                case 'CUSTOMER':
                    $data = $this->customer($request, $client, $src, $id);
                    break;
                case 'JNP':
                    $data = $this->jnp($request, $client, $src, $id);
                    break;
                case 'CBE':
                    $data = $this->cbe($request, $client, $src, $id);
                    break;
                case 'INP':
                    $data = $this->inp($request, $client, $src, $id);
                    break;
                case 'BST':
                    $data = $this->bst($request, $client, $src, $id);
                    break;
                default:
                    $data = $this->adt($request, $client, $src, $id);
                    break;
            }
            Alert::success($src . ' '. ucfirst($this->method).' successfully!');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Something went wrong!');
            // return $e->getMessage();
        }
        return redirect()->route('trash.details', [$client->id, $src]);
    }
    protected function inv($request, $client, $src, $id)
    {
        $invoice = Dedotr::onlyTrashed()->findOrFail($id);

        if (in_array($invoice->tran_date->format('m'), range(1, 6))) {
            $start_year = $invoice->tran_date->format('Y') - 1 . '-07-01';
            $end_year   = $invoice->tran_date->format('Y') . '-06-30';
        } else {
            $start_year = $invoice->tran_date->format('Y') . '-07-01';
            $end_year   = $invoice->tran_date->format('Y') + 1 . '-06-30';
        }

        $isRetain = GeneralLedger::withTrashed()->where('client_id', $invoice->client_id)
            ->where('profession_id', $invoice->profession_id)
            ->where('date', '>=', $start_year)
            ->where('date', '<=', $end_year)
            ->where('chart_id', 999999)
            ->where('source', $src)
            ->first();
        if ($isRetain) {
            $pl = GeneralLedger::onlyTrashed()->where('client_id', $invoice->client_id)
                ->where('profession_id', $invoice->profession_id)
                ->where('transaction_id', $invoice->tran_id)
                ->where('chart_id', 999998)->first();
            $rt['balance'] = $isRetain->balance + ($pl->balance ?? 0);

            if ($isRetain->credit != '') {
                $rt['credit'] = abs($rt['balance']);
                $rt['debit']  = 0;
            } else {
                $rt['debit']  = abs($rt['balance']);
                $rt['credit'] = 0;
            }
            $isRetain->update($rt);
        }
        GeneralLedger::where('client_id', $client->id)
            ->where('transaction_id', $invoice->tran_id)
            ->where('profession_id', $invoice->profession_id)
            ->where('source', $src)
            ->where('chart_id', '!=', 999999)->onlyTrashed()->{$this->method}();

        Gsttbl::where('client_id', $client->id)
            ->where('profession_id', $invoice->profession_id)
            ->where('trn_id', $invoice->tran_id)
            ->where('source', $src)->onlyTrashed()->{$this->method}();
        Dedotr::where('client_id', $client->id)
            ->where('profession_id', $invoice->profession_id)
            ->where('tran_id', $invoice->tran_id)->onlyTrashed()->{$this->method}();
    }
    protected function pin($request, $client, $src, $id)
    {
        $payment = DedotrPaymentReceive::onlyTrashed()->findOrFail($id);
        if ($payment->trashedDedotr) {
            throw new \Exception("Please restore invoice ({$payment->tran_id}) first.");
        }
        Gsttbl::where('client_id', $payment->client_id)
            ->where('profession_id', $payment->profession_id)
            ->where('trn_id', $payment->tran_id)
            ->onlyTrashed()->{$this->method}();

        $trade = GeneralLedger::where('client_id', $payment->client_id)
            ->where('profession_id', $payment->profession_id)
            ->where('transaction_id', $payment->tran_id)->withTrashed()
            ->where('chart_id', 552100)->first();

        $bank = GeneralLedger::where('client_id', $payment->client_id)
            ->where('profession_id', $payment->profession_id)
            ->where('transaction_id', $payment->tran_id)->onlyTrashed()
            ->where('source', $src)->first();

        $data['balance'] = $balance = $trade->balance - $bank->balance;
        if ($balance > 0) {
            $data['debit']  = $balance;
            $data['credit'] = 0;
        } else {
            $data['debit']  = 0;
            $data['credit'] = abs($balance);
        }
        $trade->update($data);

        GeneralLedger::where('client_id', $payment->client_id)
            ->where('profession_id', $payment->profession_id)
            ->where('transaction_id', $payment->tran_id)
            ->onlyTrashed()->{$this->method}();
        $payment->{$this->method}();
        return 'Successfully';
    }
    protected function pbp($request, $client, $src, $id)
    {
        $service_bill = Creditor::onlyTrashed()->findOrFail($id);
        if (in_array($service_bill->tran_date->format('m'), range(1, 6))) {
            $start_year = $service_bill->tran_date->format('Y') - 1 . '-07-01';
            $end_year   = $service_bill->tran_date->format('Y') . '-06-30';
        } else {
            $start_year = $service_bill->tran_date->format('Y') . '-07-01';
            $end_year   = $service_bill->tran_date->format('Y') + 1 . '-06-30';
        }

        $isRetain = GeneralLedger::where('client_id', $service_bill->client_id)
            ->where('profession_id', $service_bill->profession_id)
            ->where('date', '>=', $start_year)
            ->where('date', '<=', $end_year)
            ->where('chart_id', 999999)
            ->where('source', 'PBP')
            ->first();
        if ($isRetain) {
            $pl = GeneralLedger::onlyTrashed()->where('client_id', $service_bill->client_id)
                ->where('profession_id', $service_bill->profession_id)
                ->where('transaction_id', $service_bill->tran_id)
                ->where('chart_id', 999998)->first();
            $rt['balance']  = $isRetain->balance + $pl->balance;

            if ($isRetain->credit) {
                $rt['credit'] = abs($rt['balance']);
                $rt['debit']  = 0;
            } else {
                $rt['debit']  = abs($rt['balance']);
                $rt['credit'] = 0;
            }
            $isRetain->update($rt);
        }

        // return $rt;

        GeneralLedger::onlyTrashed()->where('client_id', $service_bill->client_id)
            ->where('profession_id', $service_bill->profession_id)
            ->where('transaction_id', $service_bill->tran_id)
            ->where('chart_id', '!=', 999999)->{$this->method}();

        Gsttbl::onlyTrashed()->where('client_id', $service_bill->client_id)
            ->where('profession_id', $service_bill->profession_id)
            ->where('trn_id', $service_bill->tran_id)->{$this->method}();

        Creditor::onlyTrashed()->where('client_id', $service_bill->client_id)
            ->where('profession_id', $service_bill->profession_id)
            ->where('tran_id', $service_bill->tran_id)->{$this->method}();

        return 'Successfully';
    }
    protected function pbn($request, $client, $src, $id)
    {
        $payment = CreditorPaymentReceive::onlyTrashed()->where('client_id', $client->id)->findOrFail($id);
        Gsttbl::onlyTrashed()->where('client_id', $payment->client_id)
            ->where('profession_id', $payment->profession_id)
            ->where('trn_id', $payment->tran_id)
            ->where('source', $src)->{$this->method}();

        $trade = GeneralLedger::where('client_id', $payment->client_id)
            ->where('profession_id', $payment->profession_id)
            ->where('transaction_id', $payment->tran_id)
            ->where('chart_id', 911999)->first();

        $bank = GeneralLedger::onlyTrashed()->where('client_id', $payment->client_id)
            ->where('profession_id', $payment->profession_id)
            ->where('transaction_id', $payment->tran_id)
            ->where('source', $src)->first();

        $data['balance'] = $balance = $trade->balance - $bank->balance;
        if ($balance < 0) {
            $data['debit']  = abs($balance);
            $data['credit'] = 0;
        } else {
            $data['debit']  = 0;
            $data['credit'] = $balance;
        }
        $trade->update($data);
        $payment->{$this->method}();
        GeneralLedger::onlyTrashed()->where('client_id', $payment->client_id)
            ->where('profession_id', $payment->profession_id)
            ->where('transaction_id', $payment->tran_id)
            ->where('source', $src)->{$this->method}();
        return 'Successfully';
    }
    protected function customer($request, $client, $src, $id)
    {
        $customer = CustomerCard::onlyTrashed()
            ->where('client_id', $client->id)
            ->findOrFail($id)->{$this->method}();
        return 'Successfully';
    }
    protected function jnp($request, $client, $src, $id)
    {
        $journal = JournalEntry::onlyTrashed()->whereClientId($client->id)->findOrFail($id);
        Gsttbl::onlyTrashed()->where('client_id', $journal->client_id)
            ->where('trn_id', $journal->tran_id)
            ->where('source', 'JNP')->{$this->method}();

        // Retains Update start
        $inRetain   = GeneralLedger::where('client_id', $journal->client_id)
            ->where('profession_id', $journal->profession_id)
            ->where('transaction_id', $journal->tran_id)
            ->where('source', 'JNP')
            ->where('chart_id', 'LIKE', '1%')->get();

        $inRetainData = $exRetainData = 0;
        foreach ($inRetain as $intr) {
            if ($intr->balance_type == 2 && $intr->balance > 0) {
                $inRetainData += abs($intr->balance);
            } else {
                $inRetainData -= abs($intr->balance);
            }
        }

        $exRetain   = GeneralLedger::where('client_id', $journal->client_id)
            ->where('profession_id', $journal->profession_id)
            ->where('transaction_id', $journal->tran_id)
            ->where('source', 'JNP')
            ->where('chart_id', 'LIKE', '2%')->get();
        foreach ($exRetain as $intr) {
            if ($intr->balance_type == 1 && $intr->balance > 0) {
                $exRetainData += abs($intr->balance);
            } else {
                $exRetainData -= abs($intr->balance);
            }
        }

        if (in_array($journal->date->format('m'), range(1, 6))) {
            $start_year = $journal->date->format('Y') - 1 . '-07-01';
            $end_year   = $journal->date->format('Y') . '-06-30';
        } else {
            $start_year = $journal->date->format('Y') . '-07-01';
            $end_year   = $journal->date->format('Y') + 1 . '-06-30';
        }
        $retain = GeneralLedger::where('client_id', $journal->client_id)
            ->where('profession_id', $journal->profession_id)
            ->where('chart_id', 999999)
            ->where('source', 'JNP')
            ->where('date', '>=', $start_year)
            ->where('date', '<=', $end_year)
            ->first();
        $retainData = $retain->balance - ($inRetainData - $exRetainData);
        $data['balance']  = $retainData;

        if ($retainData > 0) {
            $data['credit'] = abs($retainData);
            $data['debit']  = 0;
        } else {
            $data['debit']  = abs($retainData);
            $data['credit'] = 0;
        }
        $retain->update($data);
        // Retain Update end


        GeneralLedger::onlyTrashed()->where('client_id', $journal->client_id)
            ->where('profession_id', $journal->profession_id)
            ->where('transaction_id', $journal->tran_id)
            ->where('source', 'JNP')
            ->where('chart_id', '!=', 999999)->{$this->method}();



        JournalEntry::onlyTrashed()->where('client_id', $journal->client_id)
            ->where('profession_id', $journal->profession_id)
            ->where('tran_id', $journal->tran_id)
            ->where('is_posted', 1)->{$this->method}();

        return 'Successfully';
    }
    protected function inp($request, $client, $src, $id)
    {
        $input = BankStatementInput::onlyTrashed()->whereClientId($client->id)->findOrFail($id);
        $tran_id = $input->tran_id;
        Gsttbl::onlyTrashed()->where('client_id', $input->client_id)
            ->where('trn_id', $tran_id)
            ->where('source', $src)->{$this->method}();

        if ($this->method == 'restore') {
            // Retains Update start
            $inRetain   = GeneralLedger::onlyTrashed()->where('client_id', $input->client_id)
                ->where('profession_id', $input->profession_id)
                ->where('transaction_id', $tran_id)
                ->where('source', $src)
                ->where('chart_id', 'LIKE', '1%')->get();

            $inRetainData = $exRetainData = 0;
            foreach ($inRetain as $intr) {
                if ($intr->balance_type == 2 && $intr->balance > 0) {
                    $inRetainData += abs($intr->balance);
                } else {
                    $inRetainData -= abs($intr->balance);
                }
            }

            $exRetain   = GeneralLedger::onlyTrashed()->where('client_id', $input->client_id)
                ->where('profession_id', $input->profession_id)
                ->where('transaction_id', $tran_id)
                ->where('source', $src)
                ->where('chart_id', 'LIKE', '2%')->get();
            foreach ($exRetain as $intr) {
                if ($intr->balance_type == 1 && $intr->balance > 0) {
                    $exRetainData += abs($intr->balance);
                } else {
                    $exRetainData -= abs($intr->balance);
                }
            }

            if (in_array($input->date->format('m'), range(1, 6))) {
                $start_year = $input->date->format('Y') - 1 . '-07-01';
                $end_year   = $input->date->format('Y') . '-06-30';
            } else {
                $start_year = $input->date->format('Y') . '-07-01';
                $end_year   = $input->date->format('Y') + 1 . '-06-30';
            }
            $retain = GeneralLedger::where('client_id', $input->client_id)
                ->where('profession_id', $input->profession_id)
                ->where('chart_id', 999999)
                ->where('source', $src)
                ->where('date', '>=', $start_year)
                ->where('date', '<=', $end_year)
                ->first();
            $retainData = $retain->balance - ($inRetainData - $exRetainData);
            $data['balance']  = $retainData;

            if ($retainData > 0) {
                $data['credit'] = abs($retainData);
                $data['debit']  = 0;
            } else {
                $data['debit']  = abs($retainData);
                $data['credit'] = 0;
            }
            $retain->update($data);
            // Retain Update end
        }


        GeneralLedger::onlyTrashed()->where('client_id', $input->client_id)
            ->where('profession_id', $input->profession_id)
            ->where('transaction_id', $tran_id)
            ->where('source', $src)
            ->where('chart_id', '!=', 999999)->{$this->method}();



        BankStatementInput::onlyTrashed()->where('client_id', $input->client_id)
            ->where('profession_id', $input->profession_id)
            ->where('tran_id', $tran_id)
            ->where('is_posted', 1)->{$this->method}();

        return 'Successfully';
    }
    protected function bst($request, $client, $src, $id)
    {
        $input = BankStatementImport::onlyTrashed()->whereClientId($client->id)->findOrFail($id);
        $tran_id = $input->tran_id;
        Gsttbl::onlyTrashed()->where('client_id', $input->client_id)
            ->where('trn_id', $tran_id)
            ->where('source', $src)->{$this->method}();

        if ($this->method == 'restore') {
            // Retains Update start
            $inRetain   = GeneralLedger::onlyTrashed()->where('client_id', $input->client_id)
                ->where('profession_id', $input->profession_id)
                ->where('transaction_id', $tran_id)
                ->where('source', $src)
                ->where('chart_id', 'LIKE', '1%')->get();

            $inRetainData = $exRetainData = 0;
            foreach ($inRetain as $intr) {
                if ($intr->balance_type == 2 && $intr->balance > 0) {
                    $inRetainData += abs($intr->balance);
                } else {
                    $inRetainData -= abs($intr->balance);
                }
            }

            $exRetain   = GeneralLedger::onlyTrashed()->where('client_id', $input->client_id)
                ->where('profession_id', $input->profession_id)
                ->where('transaction_id', $tran_id)
                ->where('source', $src)
                ->where('chart_id', 'LIKE', '2%')->get();
            foreach ($exRetain as $intr) {
                if ($intr->balance_type == 1 && $intr->balance > 0) {
                    $exRetainData += abs($intr->balance);
                } else {
                    $exRetainData -= abs($intr->balance);
                }
            }

            if (in_array($input->date->format('m'), range(1, 6))) {
                $start_year = $input->date->format('Y') - 1 . '-07-01';
                $end_year   = $input->date->format('Y') . '-06-30';
            } else {
                $start_year = $input->date->format('Y') . '-07-01';
                $end_year   = $input->date->format('Y') + 1 . '-06-30';
            }
            $retain = GeneralLedger::where('client_id', $input->client_id)
                ->where('profession_id', $input->profession_id)
                ->where('chart_id', 999999)
                ->where('source', $src)
                ->where('date', '>=', $start_year)
                ->where('date', '<=', $end_year)
                ->first();
            $retainData = $retain->balance - ($inRetainData - $exRetainData);
            $data['balance']  = $retainData;

            if ($retainData > 0) {
                $data['credit'] = abs($retainData);
                $data['debit']  = 0;
            } else {
                $data['debit']  = abs($retainData);
                $data['credit'] = 0;
            }
            $retain->update($data);
            // Retain Update end
        }


        GeneralLedger::onlyTrashed()->where('client_id', $input->client_id)
            ->where('profession_id', $input->profession_id)
            ->where('transaction_id', $tran_id)
            ->where('source', $src)
            ->where('chart_id', '!=', 999999)->{$this->method}();



        BankStatementImport::onlyTrashed()->where('client_id', $input->client_id)
            ->where('profession_id', $input->profession_id)
            ->where('tran_id', $tran_id)
            ->where('is_posted', 1)->{$this->method}();

        return 'Successfully';
    }
    protected function cbe($request, $client, $src, $id)
    {
        $cashbook = CashBook::onlyTrashed()->whereClientId($client->id)->findOrFail($id)->{$this->method}();
        return 'Successfully';
    }
    protected function adt($request, $client, $src, $id)
    {
        return 'Successfully';
    }
}
