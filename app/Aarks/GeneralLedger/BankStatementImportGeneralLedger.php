<?php namespace App\Aarks\GeneralLedger;

class BankStatementImportGeneralLedger extends AbstractGeneralLedger
{
    public function __construct()
    {
        $this->setSource('BST')
            ->setValidGstCodes(['GST','INP','CAP'])
            ->setGstAccountCodes([
                'GST' => env('GST_PAYABLE_ACCOUNT_CODE'),
                'CAP' => env('GST_CLEARING_ACCOUNT_CODE'),
                'INP' => env("GST_CLEARING_ACCOUNT_CODE")
            ]);
    }

    protected function calculateGST()
    {
        $data = $this->getCurrentData();
        $value = $data['credit'] > 0 ? $data['credit'] : $data['debit'];
        return $value / 11;
    }

    protected function isGstApplicable()
    {
        $gst_code = $this->getCurrentData()['client_account_code']['gst_code'];
        return $this->client->is_gst_enabled && in_array($gst_code, $this->getValidGstCodes());
    }

    protected function calculateGstAccountCode()
    {
        if ($this->getCurrentData()['credit'] > 0) {
            return $this->gst_account_code_objects->where('code', env('GST_PAYABLE_ACCOUNT_CODE'))->first();
        } else {
            return $this->gst_account_code_objects->where('code', env('GST_CLEARING_ACCOUNT_CODE'))->first();
        }
    }
}
