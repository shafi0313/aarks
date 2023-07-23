<?php namespace App\Actions\ClientActions;

use App\Models\Payg;
use App\Models\Gsttbl;
use App\Models\Payable;
use App\Models\FuelTaxLtr;
use App\Actions\BaseAction;
use App\Models\Data_storage;
use App\Models\DepAssetName;
use App\Models\Depreciation;
use App\Models\JournalEntry;
use App\Models\GeneralLedger;
use App\Models\Frontend\Dedotr;
use App\Models\Frontend\Payslip;
use App\Models\ClientPaymentList;
use App\Models\Frontend\Ato_data;
use App\Models\BankStatementInput;
use Illuminate\Support\Facades\DB;
use App\Models\BankStatementImport;
use App\Models\Frontend\Dedotr_job;
use App\Models\Frontend\Dedotr_qtc;
use App\Models\Frontend\PayAccumAmt;
use App\Models\Frontend\CustomerCard;
use App\Models\Frontend\EmployeeCard;
use App\Models\Frontend\PayslipDeduc;
use App\Models\Frontend\PayslipLeave;
use App\Models\Frontend\PayslipSuper;
use App\Models\Frontend\PayslipWages;
use App\Models\Frontend\PreparePayroll;
use App\Models\Frontend\EClassification;

class DeleteClient extends BaseAction
{
    private $client;

    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    public function execute()
    {
        $this->client->services()->delete();
        $this->client->periods()->delete();
        $this->client->account_codes()->delete();
        // $this->client->professions()->delete();
        $this->client->paylist()->delete();
        $this->client->wages()->delete();
        $this->client->deduction()->delete();
        $this->client->super()->delete();
        $this->client->leave()->delete();
        $this->client->invoiceLayout()->delete();
        Ato_data::where('client_id', $this->client->id)->delete();
        BankStatementImport::where('client_id', $this->client->id)->delete();
        BankStatementInput::where('client_id', $this->client->id)->delete();
        ClientPaymentList::where('client_id', $this->client->id)->delete();
        CustomerCard::where('client_id', $this->client->id)->delete();
        Data_storage::where('client_id', $this->client->id)->delete();
        Dedotr::where('client_id', $this->client->id)->delete();
        Dedotr_job::where('client_id', $this->client->id)->delete();
        Dedotr_qtc::where('client_id', $this->client->id)->delete();
        Depreciation::where('client_id', $this->client->id)->delete();
        DepAssetName::where('client_id', $this->client->id)->delete();
        EmployeeCard::where('client_id', $this->client->id)->delete();
        EClassification::where('client_id', $this->client->id)->delete();
        FuelTaxLtr::where('client_id', $this->client->id)->delete();
        GeneralLedger::where('client_id', $this->client->id)->delete();
        Gsttbl::where('client_id', $this->client->id)->delete();
        JournalEntry::where('client_id', $this->client->id)->delete();
        Payable::where('client_id', $this->client->id)->delete();
        Payg::where('client_id', $this->client->id)->delete();
        $Payslip = Payslip::where('client_id', $this->client->id);
        if ($Payslip->first() != '') {
            PayslipDeduc::where('tran_id', $Payslip->first()->tran_id)->delete();
            PayslipLeave::where('tran_id', $Payslip->first()->tran_id)->delete();
            PayslipSuper::where('tran_id', $Payslip->first()->tran_id)->delete();
            PayslipWages::where('tran_id', $Payslip->first()->tran_id)->delete();
            $Payslip->delete();
        }
        PayAccumAmt::where('client_id', $this->client->id)->delete();
        PreparePayroll::where('client_id', $this->client->id)->delete();
        $this->client->delete();
    }
}
