<?php namespace App\Actions\MasterChartActions;

use App\Actions\Creatable;
use App\Models\MasterAccountCode;
use App\Actions\AccountCodeActions\AddProfessionAccountCode;

class AddMasterChart extends AddProfessionAccountCode
{
    use Creatable;

    public function __construct(MasterAccountCode $masterAccountCode)
    {
        $this->setModel($masterAccountCode);
    }

    public function execute()
    {
        $this->create();
    }
}
