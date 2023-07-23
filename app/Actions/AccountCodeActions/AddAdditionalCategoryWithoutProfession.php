<?php namespace App\Actions\AccountCodeActions;


class AddAdditionalCategoryWithoutProfession extends AddAdditionalCategory
{
    public function execute()
    {
        $account_code = $this->create();

        $this->assign_industry_category_action
            ->setInstance($account_code)
            ->setIndustryCategory($this->data['industry_category'])
            ->execute();

        return $account_code;
    }

}
