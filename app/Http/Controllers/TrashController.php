<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Client;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use App\Models\Frontend\Dedotr;
use App\Models\Frontend\CashBook;
use App\Models\Frontend\Creditor;
use App\Http\Controllers\Controller;
use App\Models\BankStatementImport;
use App\Models\BankStatementInput;
use App\Models\Frontend\CustomerCard;
use App\Models\Frontend\DedotrPaymentReceive;
use App\Models\Frontend\CreditorPaymentReceive;

class TrashController extends Controller
{
    // protected $get;
    public function __construct()
    {
        $this->middleware('auth:admin');
        // $get = request()->method() == "GET"?"get":"forceDelete";
    }
    public function index()
    {
        $clients = Client::get();
        return view('admin.trashed.client', compact('clients'));
    }
    public function source(Request $request, Client $client)
    {
        return view('admin.trashed.source', compact('client'));
    }
    public function details(Request $request, Client $client, $src)
    {
        $data = '';
        switch ($src) {
            case 'INV':
                $data = $this->inv($request, $client, $src);
                break;
            case 'PIN':
                $data = $this->pin($request, $client, $src);
                break;
            case 'PBP':
                $data = $this->pbp($request, $client, $src);
                break;
            case 'PBN':
                $data = $this->pbn($request, $client, $src);
                break;
            case 'CUSTOMER':
                $data = $this->customer($request, $client, $src);
                break;
            case 'JNP':
                $data = $this->jnp($request, $client, $src);
                break;
            case 'CBE':
                $data = $this->cbe($request, $client, $src);
                break;
            case 'INP':
                $data = $this->inp($request, $client, $src);
                break;
            case 'BST':
                $data = $this->bst($request, $client, $src);
                break;
            default:
                $data = $this->adt($request, $client, $src);
                break;
        }
        // return $data;
        return view('admin.trashed.details', $data);
    }
    protected function inv(Request $request, Client $client, $src)
    {
        $ledgers = Dedotr::filter()->with(['payments' => function ($q) use ($client) {
            return $q->where('client_id', $client->id);
        }, 'customer'])->where('client_id', $client->id)
            ->where('chart_id', 'not like', '551%')->onlyTrashed()->get();
            // ->where('job_title', '!=', '')->get();
        return [
            'src'     => $src,
            'client'  => $client,
            'request' => $request,
            'ledgers' => $ledgers,
        ];
    }
    protected function pin(Request $request, Client $client, $src)
    {
        $ledgers = DedotrPaymentReceive::with('customer')->where('client_id', $client->id)
            ->onlyTrashed()->get();
        return [
            'src'     => $src,
            'client'  => $client,
            'request' => $request,
            'ledgers' => $ledgers,
        ];
    }
    protected function pbp(Request $request, Client $client, $src)
    {
        $ledgers =  Creditor::with(['payments' => function ($q) use ($client) {
            return $q->where('client_id', $client->id);
        }])->where('client_id', $client->id)
            ->where('chart_id', 'not like', '551%')->onlyTrashed()->get();
        return [
            'src'     => $src,
            'client'  => $client,
            'request' => $request,
            'ledgers' => $ledgers,
        ];
    }
    protected function pbn(Request $request, Client $client, $src)
    {
        $ledgers = CreditorPaymentReceive::with('customer')->where('client_id', $client->id)
            ->onlyTrashed()->get();
        return [
            'src'     => $src,
            'client'  => $client,
            'request' => $request,
            'ledgers' => $ledgers,
        ];
    }
    protected function customer(Request $request, Client $client, $src)
    {
        $ledgers = CustomerCard::onlyTrashed()->where('client_id', $client->id)->get();
        return [
            'src'     => $src,
            'client'  => $client,
            'request' => $request,
            'ledgers' => $ledgers,
        ];
    }
    protected function jnp(Request $request, Client $client, $src)
    {
        $ledgers = JournalEntry::onlyTrashed()->where('client_id', $client->id)
            ->where('is_posted', 1)->orderBy('journal_number')->get();
        return [
            'src'     => $src,
            'client'  => $client,
            'request' => $request,
            'ledgers' => $ledgers,
        ];
    }
    protected function cbe(Request $request, Client $client, $src)
    {
        $ledgers = CashBook::onlyTrashed()->with('accountCode')->where('client_id', $client->id)
        // ->where('is_save', 1)->where('is_post', 1)
        ->orderBy('tran_date')->get();
        return [
            'src'     => $src,
            'client'  => $client,
            'request' => $request,
            'ledgers' => $ledgers,
        ];
    }
    protected function inp(Request $request, Client $client, $src)
    {
        $ledgers = BankStatementInput::onlyTrashed()->with('client_account_code')->where('client_id', $client->id)->where('is_posted', 1)
        ->orderBy('date')->get()->groupBy('tran_id');
        return [
            'src'     => $src,
            'client'  => $client,
            'request' => $request,
            'ledgers' => $ledgers,
        ];
    }
    protected function bst(Request $request, Client $client, $src)
    {
        $ledgers = BankStatementImport::onlyTrashed()->with('client_account_code')->where('client_id', $client->id)->where('is_posted', 1)->orderBy('date')->get()->groupBy('tran_id');
        return [
            'src'     => $src,
            'client'  => $client,
            'request' => $request,
            'ledgers' => $ledgers,
        ];
    }
    protected function adt(Request $request, Client $client, $src)
    {
        $ledgers = 'MANOAR';
        return [
            'src'     => $src,
            'client'  => $client,
            'request' => $request,
            'ledgers' => $ledgers,
        ];
    }
}
