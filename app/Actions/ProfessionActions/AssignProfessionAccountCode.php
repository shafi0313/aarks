<?php namespace App\Actions\ProfessionActions;

use App\Actions\BaseAction;

class AssignProfessionAccountCode extends BaseAction
{
    private $account_codes;

    public function execute()
    {
        $this->getModel()->accountCodes()->sync($this->data());
    }

    public function setAccountCodes($account_codes)
    {
        $this->account_codes = $account_codes;
        return $this;
    }

    public function setInstance($instance)
    {
        $this->setModel($instance);
        return $this;
    }

    private function data()
    {
        return is_array($this->account_codes) ? $this->account_codes : [$this->account_codes];
    }
}
