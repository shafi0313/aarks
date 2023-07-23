<?php namespace App\Actions\SharedActions;

use App\Actions\BaseAction;


class AssignIndustryCategory extends BaseAction
{
    private $industry_category;

    public function execute()
    {
        $this->getModel()->industryCategories()->sync($this->data());
    }

    public function setIndustryCategory($industry_category)
    {
        $this->industry_category = $industry_category;
        return $this;
    }

    public function setInstance($instance)
    {
        $this->setModel($instance);
        return $this;
    }

    private function data()
    {
        try {
            return is_array($this->industry_category) ? $this->industry_category : [$this->industry_category];
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }

    }
}
