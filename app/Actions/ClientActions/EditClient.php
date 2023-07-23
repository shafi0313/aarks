<?php namespace App\Actions\ClientActions;

use App\Actions\BaseAction;
use App\Actions\Editable;
use App\Actions\SharedActions\AssignProfession;
use App\Models\Client;
use Illuminate\Support\Arr;

class EditClient extends BaseAction
{
    use Editable;
    private $assign_service_action;
    private $assign_profession_action;

    public function __construct(AssignService $assign_service_action, AssignProfession $assign_profession_action)
    {
        $this->assign_service_action = $assign_service_action;
        $this->assign_profession_action = $assign_profession_action;
    }

    public function execute()
    {
        $this->edit();

        $this->assign_service_action
            ->setServices($this->data['services'])
            ->setInstance($this->getModel())
            ->execute();

        $this->assign_profession_action
            ->setProfessions($this->data['professions'])
            ->setInstance($this->getModel())
            ->execute();
    }

    private function data()
    {
        return Arr::except($this->data, ['services', 'professions']);
    }
}
