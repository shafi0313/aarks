<?php namespace App\Actions\BankStatementActions;


use Carbon\Carbon;
use App\Models\Client;
use App\Models\Profession;
use App\Actions\BaseAction;
use App\Models\ClientAccountCode;
use App\Models\BankStatementImport;
use App\Aarks\GeneralLedger\InvalidValueException;
use App\Aarks\GeneralLedger\BankStatementImportGeneralLedger;

class ImportBankStatement extends BaseAction
{
    private $client, $profession, $bank_account;
    private $bankStatementImportGeneralLedger;

    public function __construct(BankStatementImportGeneralLedger $bankStatementGeneralLedger)
    {
        $this->bankStatementImportGeneralLedger = $bankStatementGeneralLedger;
    }

    public function execute()
    {
        dd($this->getUnPostedBankImportsAsArray());
        $this->validateRequirements();
        try {
            $this->bankStatementImportGeneralLedger
                ->setProfession($this->profession)
                ->setClient($this->client)
                ->setData($this->getUnPostedBankImportsAsArray())
                ->setOppositeAccountCode($this->bank_account)
                ->generateLedger();
            $this->updateBankStatementPostStatus();
        } catch (InvalidValueException $exception) {
            throw new \Exception("Something went wrong");
        }
    }

    private function validateRequirements()
    {
        if (!($this->client instanceof Client)) {
            throw new \Exception("Client Not Set Yet");
        }

        if (!($this->profession instanceof Profession)) {
            throw new \Exception("Profession Not Set Yet");
        }

        if (!($this->bank_account instanceof ClientAccountCode)) {
            throw new \Exception("Bank Account Not Set Yet");
        }
    }

    private function updateBankStatementPostStatus()
    {
        return BankStatementImport::where('is_posted', 0)->where('client_id', $this->client->id)
            ->where('profession_id', $this->profession->id)
            ->update(['is_posted' => 1]);
    }

    private function getUnPostedBankImportsAsArray()
    {
        $bank_statements = BankStatementImport::with('client_account_code')
            ->where('is_posted', 0)->where('client_id', $this->client->id)
            ->where('profession_id', $this->profession->id)
            ->get()
            ->toArray();

        foreach($bank_statements as &$bank_statement){
            $bank_statement['date'] = Carbon::create($bank_statement['date'])->toDateString();
        }
        return $bank_statements;
    }

    /**
     * @param mixed $client
     * @return ImportBankStatement
     */
    public function setClient(Client $client): ImportBankStatement
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param mixed $profession
     * @return ImportBankStatement
     */
    public function setProfession(Profession $profession): ImportBankStatement
    {
        $this->profession = $profession;
        return $this;
    }

    /**
     * @param mixed $bank_account
     * @return ImportBankStatement
     */
    public function setBankAccount(ClientAccountCode $bank_account): ImportBankStatement
    {
        $this->bank_account = $bank_account;
        return $this;
    }
}
