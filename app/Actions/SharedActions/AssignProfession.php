<?php namespace App\Actions\SharedActions;

use App\Actions\BaseAction;


class AssignProfession extends BaseAction
{
    private $professions;

    public function execute()
    {
        $this->getModel()->professions()->sync($this->data());
    }

    public function setProfessions($professions)
    {
        $this->professions = $professions;
        return $this;
    }

    public function setInstance($instance)
    {
        $this->setModel($instance);
        return $this;
    }

    private function data()
    {
        return is_array($this->professions) ? $this->professions : [$this->professions];
    }

}
