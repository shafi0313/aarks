<?php

namespace App\Exports;

use App\Models\BankReconciliation;
use Illuminate\Contracts\View\View;
use App\Models\BankReconciliationAdmin;
use App\Models\BankReconciliationLedger;
use Maatwebsite\Excel\Concerns\FromView;

class BankReconcilationExport implements FromView
{
    public $client;
    public $data;
    public $type;
    public function __construct($client, $data, $type = 'admin')
    {
        $this->client = $client;
        $this->data   = $data;
        $this->type   = $type;
    }
    public function view(): View
    {
        if ($this->type == 'admin') {
            return view('admin.reports.bank_recon.export', [
                'reconcilations' => BankReconciliationAdmin::whereClientId($this->client->id)
                        ->whereIn('tran_id', $this->data['tran_id'])
                        // ->where('is_posted', 1)
                        // ->where('diff', '!=', 0)
                        ->get(),
                'client'     => $this->client,
            ]);
        } else {
            return view('frontend.banking.reconciliation.export', [
                'reconcilations' => BankReconciliationLedger::with('generalLedger')->whereClientId($this->data['client_id'])
                    ->where('profession_id', $this->data['profession_id'])
                    ->whereIn('general_ledger_id', $this->data['ledger_id'])
                    ->where('is_posted', 0)
                    ->where('date', '<=', $this->data['date']->format('Y-m-d'))
                    ->get(),
                'bank_recon' => BankReconciliation::whereClientId($this->data['client_id'])
                ->where('profession_id', $this->data['profession_id'])
                ->where('is_posted', 0)
                ->where('date', '<=', $this->data['date']->format('Y-m-d'))
                ->first(),
                'client'         => $this->client,
            ]);
        }
    }
}
