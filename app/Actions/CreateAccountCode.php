<?php

namespace App\Actions;

use App\Models\AccountCodeCategory;

trait CreateAccountCode
{
    protected function generateAccountCode()
    {
        $category_code            = AccountCodeCategory::find($this->data['category_id'])->code;
        $sub_category_code        = AccountCodeCategory::find($this->data['sub_category_id'])->code;
        $additional_category_code = AccountCodeCategory::find($this->data['additional_category_id'])->code;

        return $category_code . $sub_category_code . $additional_category_code . $this->data['code'];
    }
}
