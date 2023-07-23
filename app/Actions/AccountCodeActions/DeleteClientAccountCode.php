<?php


namespace App\Actions\AccountCodeActions;

use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\Payable;
use App\Actions\BaseAction;
use App\Models\Data_storage;
use App\Models\DepAssetName;
use App\Models\Depreciation;
use App\Models\JournalEntry;
use App\Models\GeneralLedger;
use App\Models\Frontend\Dedotr;
use App\Models\Frontend\Measure;
use App\Models\ClientAccountCode;
use App\Models\Frontend\CashBook;
use App\Models\Frontend\Creditor;
use App\Models\BankStatementInput;
use App\Models\Frontend\Recurring;
use App\Models\BankStatementImport;
use App\Models\Frontend\CashOffice;
use App\Models\Frontend\PayAccumAmt;
use App\Models\Frontend\CustomerCard;
use App\Models\Frontend\EmployeeCard;
use App\Models\Frontend\InventoryItem;
use App\Models\Frontend\PreparePayroll;
use App\Models\Frontend\DedotrQuoteOrder;
use App\Models\Frontend\InventoryCategory;
use App\Models\Frontend\InventoryRegister;
use App\Models\Frontend\CreditorServiceOrder;

class DeleteClientAccountCode extends BaseAction
{
    private $professions;
    private $client;
    public function setData($professions, $client)
    {
        $this->professions = $professions;
        $this->client = $client;
        return $this;
    }

    public function execute()
    {
        $account_codes = ClientAccountCode::where('client_id', $this->client)
           ->whereIn('profession_id', $this->professions)->get();

        Measure::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        InventoryRegister::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        InventoryItem::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        InventoryCategory::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        CashBook::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        CashOffice::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        CreditorServiceOrder::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        Creditor::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        Recurring::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        DedotrQuoteOrder::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        Dedotr::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        PayAccumAmt::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        PreparePayroll::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        CustomerCard::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        EmployeeCard::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        DepAssetName::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        Depreciation::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        JournalEntry::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        Payable::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        BankStatementInput::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        BankStatementImport::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        Period::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        Data_storage::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        Gsttbl::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();
        GeneralLedger::where('client_id', $this->client)
        ->whereIn('profession_id', $this->professions)->delete();

        foreach ($account_codes as $account_code) {
            $account_code->delete();
        }
    }
}
