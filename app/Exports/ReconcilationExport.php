<?php

namespace App\Exports;

use App\Models\Reconcilation;
use App\Models\ReconcilationTax;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReconcilationExport implements FromView
{
    public $client;
    public $profession;
    public $period;
    public function __construct($client, $profession, $period)
    {
        $this->client     = $client;
        $this->profession = $profession;
        $this->period     = $period;
    }
    public function view(): View
    {
        return view('admin.reports.gst_recon.export', [
            'reconcilations' => Reconcilation::whereClientId($this->client->id)
                ->whereProfessionId($this->profession->id)
                ->wherePeriodId($this->period->id)
                ->where('year', $this->period->year)
                ->get(),
            'taxes' => ReconcilationTax::whereClientId($this->client->id)
                ->whereProfessionId($this->profession->id)
                ->wherePeriodId($this->period->id)
                ->where('year', $this->period->year)
                ->get(),
            'client'     => $this->client,
            'profession' => $this->profession,
            'period'     => $this->period,
        ]);
    }
}
