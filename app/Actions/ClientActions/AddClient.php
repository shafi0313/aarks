<?php namespace App\Actions\ClientActions;

use App\Actions\BaseAction;
use App\Actions\Creatable;
use App\Actions\SharedActions\AssignProfession;
use App\Models\Client;
use Illuminate\Support\Arr;

class AddClient extends BaseAction
{
    use Creatable;
    private $assign_service_action;
    private $assign_profession_action;

    public function __construct(AssignService $assign_service_action, AssignProfession $assign_profession_action)
    {
        $this->setModel(new Client());
        $this->assign_service_action = $assign_service_action;
        $this->assign_profession_action = $assign_profession_action;
    }

    public function execute()
    {
        $client = $this->create();
        $this->assign_service_action
            ->setServices($this->data['services'])
            ->setInstance($client)
            ->execute();

        $this->assign_profession_action
            ->setProfessions($this->data['professions'])
            ->setInstance($client)
            ->execute();
        return $client;
    }

    protected function data()
    {
        return Arr::except($this->data, ['services', 'professions']);
    }
}
