<?php namespace App\Actions\AccountCodeActions;

use App\Actions\BaseAction;
use App\Actions\Editable;

class EditAccountCodeAction extends BaseAction
{
    use Editable;

    public function execute()
    {
        $this->edit();
    }
}
