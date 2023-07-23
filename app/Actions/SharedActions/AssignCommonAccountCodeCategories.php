<?php namespace App\Actions\SharedActions;

use App\Actions\BaseAction;
use App\Models\AccountCodeCategory;



class AssignCommonAccountCodeCategories extends BaseAction
{
    private $account_code_categories;

    public function execute()
    {
        $this->getModel()->accountCodeCategories()->sync($this->commonAccountCodeCategories());
    }

    public function setInstance($instance)
    {
        $this->setModel($instance);
        return $this;
    }

    private function commonAccountCodeCategories()
    {
        return AccountCodeCategory::where('is_for_all_professions', true)->pluck('id')->toArray();
    }

}
