<?php namespace App\Actions\ProfessionActions;

use App\Actions\Creatable;
use App\Models\Profession;
use App\Actions\BaseAction;
use App\Actions\SharedActions\AssignIndustryCategory;
use App\Actions\SharedActions\AssignCommonAccountCodeCategories;

class AddProfession extends BaseAction
{
    use Creatable;
    private $assign_industry_category;
    private $assign_common_account_code_categories;

    public function __construct(
        Profession $profession,
        AssignIndustryCategory $assign_industry_category,
        AssignCommonAccountCodeCategories $assign_common_account_code_categories
    ) {
        $this->setModel($profession);
        $this->assign_industry_category = $assign_industry_category;
        $this->assign_common_account_code_categories = $assign_common_account_code_categories;
    }

    protected function data()
    {
        return ['name' => $this->data['name']];
    }

    public function execute()
    {
        $profession = $this->create();
        $this->assign_industry_category
            ->setInstance($profession)
            ->setIndustryCategory($this->data['industry_category'])
            ->execute();

        $this->assign_common_account_code_categories
            ->setInstance($profession)
            ->execute();

        return $profession;
    }
}
