<?php namespace App\Actions\AccountCodeActions;

use App\Actions\Creatable;
use App\Actions\BaseAction;
use Illuminate\Support\Arr;
use App\Models\AccountCodeCategory;
use App\Actions\SharedActions\AssignProfession;
use App\Actions\SharedActions\AssignIndustryCategory;

class AddAdditionalCategory extends BaseAction
{

    use Creatable;
   public $assign_industry_category_action;
    private $assign_profession_action;

    public function __construct(AssignIndustryCategory $assign_industry_category_action, AssignProfession $assign_profession_action)
    {
        $this->setModel(new AccountCodeCategory());
        $this->assign_industry_category_action = $assign_industry_category_action;
        $this->assign_profession_action = $assign_profession_action;
    }

    public function execute()
    {
        $additional_category = $this->create();
        $this->assign_industry_category_action
            ->setIndustryCategory($this->data['industry_category'])
            ->setInstance($additional_category)
            ->execute();

        $this->assign_profession_action
            ->setProfessions($this->data['profession_id'])
            ->setInstance($additional_category)
            ->execute();
        return $additional_category;
    }

    protected function data()
    {
        return Arr::except($this->data, ['industry_category', 'profession_id']);
    }
}
