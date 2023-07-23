<?php namespace App\Actions;

use Illuminate\Database\Eloquent\Model;

trait Editable
{
    protected $data;

    private function edit()
    {
        $this->getModel()->update($this->data());
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function setInstance($instance)
    {
        $this->setModel($instance);
        return $this;
    }

    private function data()
    {
        return $this->data;
    }
}
