<?php namespace App\Aarks\GeneralLedger;

class BankStatementInputGeneralLedger extends BankStatementImportGeneralLedger
{
    protected function isGstApplicable()
    {
        $gst_code = $this->getCurrentData()['gst_code'];
        return $this->client->is_gst_enabled && in_array($gst_code, $this->getValidGstCodes());
    }
}
