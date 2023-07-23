<?php namespace App\Actions\ProfessionActions;

use App\Actions\BaseAction;
use App\Actions\Editable;

class EditProfession extends BaseAction
{
    use Editable;

    private $assign_industry_category;
    private $assign_common_account_code_categories;

    private function data()
    {
        $data = ['name' => $this->data['name']];

        if (isset($this->data['is_master_account_code_synced'])) {
            $data['is_master_account_code_synced'] = $this->data['is_master_account_code_synced'];
        }

        if (isset($this->data['can_perform_sync'])) {
            $data['can_perform_sync'] = $this->data['can_perform_sync'];
        }

        return $data;
    }

    public function execute()
    {
        $profession = $this->edit();

        return $profession;
    }
}
