<?php namespace App\Actions;

trait Creatable
{
    protected $data;

    protected function create()
    {
        if (!$this->data) {
            throw new \Exception("Data Need to Set First");
        }
        return $this->getModel()->create($this->data());
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    protected function data()
    {
        return $this->data;
    }
}
