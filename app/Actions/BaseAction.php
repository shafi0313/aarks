<?php namespace App\Actions;

use Illuminate\Database\Eloquent\Model;

abstract class BaseAction implements ActionContract
{
    private $model;

    protected function setModel(Model $model)
    {
        $this->model = $model;
    }

    protected function getModel()
    {
        if (!$this->model) {
            throw new \Exception("Model Need to Set First");
        }
        return $this->model;
    }
}
