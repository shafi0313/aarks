<?php namespace App\Actions\BankStatementActions;

use App\Models\Client;
use App\Models\Profession;
use App\Actions\BaseAction;
use App\Models\ClientAccountCode;
use App\Models\BankStatementInput;
use App\Aarks\GeneralLedger\InvalidValueException;
use App\Aarks\GeneralLedger\BankStatementInputGeneralLedger;

class InputBankStatementPost extends BaseAction
{
    private $client;
    private $profession;
    private $bank_account;
    private $bankStatementInputGeneralLedger;

    public function __construct(BankStatementInputGeneralLedger $bankStatementGeneralLedger)
    {
        $this->bankStatementInputGeneralLedger = $bankStatementGeneralLedger;
    }

    public function execute()
    {
        $this->validateRequirements();
        try {
            $this->bankStatementInputGeneralLedger
                ->setProfession($this->profession)
                ->setClient($this->client)
                ->setData($this->getUnPostedBankImportsAsArray())
                ->setOppositeAccountCode($this->bank_account)
                ->generateLedger();
            // $this->updateBankStatementPostStatus();
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
        return BankStatementInput::where('is_posted', 0)->where('client_id', $this->client->id)
            ->where('profession_id', $this->profession->id)
            ->update(['is_posted' => 1]);
    }

    private function getUnPostedBankImportsAsArray()
    {
        return BankStatementInput::with('client_account_code')
            ->where('is_posted', 0)->where('client_id', $this->client->id)
            ->where('profession_id', $this->profession->id)
            ->get()
            ->toArray();
    }

    /**
     * @param mixed $client
     * @return InputBankStatementPost
     */
    public function setClient(Client $client): InputBankStatementPost
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param mixed $profession
     * @return InputBankStatementPost
     */
    public function setProfession(Profession $profession): InputBankStatementPost
    {
        $this->profession = $profession;
        return $this;
    }

    /**
     * @param mixed $bank_account
     * @return InputBankStatementPost
     */
    public function setBankAccount(ClientAccountCode $bank_account): InputBankStatementPost
    {
        $this->bank_account = $bank_account;
        return $this;
    }
}
