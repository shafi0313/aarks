<?php namespace App\Jobs;

use App\Models\Client;
use App\ClientAccountCode;
use App\Models\GeneralLedger;
use App\Profession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateGeneralLedgerBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client;
    protected $profession;

    /**
     * Create a new job instance.
     *
     * @param Client $client
     * @param Profession $profession
     */
    public function __construct(Client $client, Profession $profession)
    {
        $this->client = $client;
        $this->profession = $profession;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client_account_codes = ClientAccountCode::where('client_id', $this->client->id)
            ->where('profession_id', $this->profession->id)
            ->get();
        foreach ($client_account_codes as $client_account_code) {
            $general_ledger = GeneralLedger::with('client_account_code')
                ->where('client_id', $this->client->id)
                ->where('profession_id', $this->profession->id)
                ->where('client_account_code_id', $client_account_code->id)
                ->orderBy('date')
                ->get();

            $this->calculateBalance($general_ledger, $client_account_code);
        }
    }

    private function calculateBalance($generalLedger, ClientAccountCode $clientAccountCode)
    {
        $previous_financial_year = $generalLedger->count() ? getFinancialYearOf($generalLedger->first()->date) : null;
        $total_debit_amount = 0;
        $total_credit_amount = 0;
        foreach ($generalLedger as $transaction) {
            $current_financial_year = getFinancialYearOf($transaction->date);

            if (in_array($clientAccountCode->category_id, [1, 3]) && $previous_financial_year != $current_financial_year) {
                $total_debit_amount = 0;
                $total_credit_amount = 0;
            }
            $total_credit_amount += $transaction->credit ? $transaction->credit : 0;
            $total_debit_amount += $transaction->debit ? $transaction->debit : 0;

            $transaction->balance = abs($total_debit_amount - $total_credit_amount);
            $transaction->balance_type = ($total_credit_amount > $total_debit_amount) ? 0 : 1;
            $transaction->save();

            $previous_financial_year = $current_financial_year;
        }
    }
}
