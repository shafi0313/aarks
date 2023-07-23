<?php namespace App\Rules;

use App\AccountCodeCategory;
use Illuminate\Contracts\Validation\Rule;

class DuplicateAccountCodeCategory implements Rule
{

    private $type;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $existingCategory = AccountCodeCategory::where('type', $this->type)
            ->where('code', request('single_account_code'))
            ->whereHas('industryCategories', function ($query) {
                return $query->where('industry_category_id', request('industry_category'));
            });
        if ($this->type == 2) {
            $existingCategory = $existingCategory->where('parent_id', request('category'));
        }
        elseif ($this->type == 3) {
            $existingCategory = $existingCategory->where('parent_id', request('sub_category'));
        }
        $existingCategory = $existingCategory->count();

        return !$existingCategory;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Account Code Exists';
    }
}
