<?php
namespace Database\Seeders;

use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AccountCodeCategory;
use App\Actions\SharedActions\AssignIndustryCategory;

class AccountCodeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param AssignIndustryCategory $assignIndustryCategory
     * @return void
     */
    public function run(AssignIndustryCategory $assignIndustryCategory)
    {
        $categories = [
            [
                'data' => [
                    'name' => 'Income',
                    'code' => 1,
                    'type' => 1,
                    'is_deletable' => false,
                    'is_for_all_professions' => true
                ],
                'sub_categories' => [
                    ['name' => 'Manufacturing Revenue', 'code' => 1, 'type' => 2, 'industry_category_id' => [1], 'note' => 'This Sales apply only for Manufacturing business'],
                    ['name' => 'Sales(Trading Revenue)', 'code' => 2, 'type' => 2, 'industry_category_id' => [2], 'note' => 'Sales Item With GST(Please enter from bank statement of Till Summary)'],
                    ['name' => 'Service Provided Income', 'code' => 3, 'type' => 2, 'industry_category_id' => [3], 'note' => 'All Professional Service Income,Like Accountant,Lawyer,Engineer income etc'],
                    ['name' => 'Other Income', 'code' => 9, 'type' => 2, 'industry_category_id' => [1, 2, 3], 'note' => 'All type of Commission/Royality Received include in this code'],
                ]
            ],
            [
                'data' => [
                    'name' => 'Expense',
                    'code' => 2,
                    'type' => 1,
                    'is_deletable' => false,
                    'is_for_all_professions' => true
                ],
                'sub_categories' => [
                    ['name' => 'Manufacturing Expense', 'code' => 1, 'type' => 2, 'industry_category_id' => [1], 'note' => 'Last year Closing stock balance should show in here'],
                    ['name' => 'Trading Expenses', 'code' => 2, 'type' => 2, 'industry_category_id' => [2]],
                    ['name' => 'Service Expenses', 'code' => 3, 'type' => 2, 'industry_category_id' => [3]],
                    ['name' => 'Overhead', 'code' => 4, 'type' => 2, 'industry_category_id' => [1, 2, 3]],
                ]
            ],
            [
                'data' => [
                    'name' => 'Asset and property',
                    'code' => 5,
                    'type' => 1,
                    'is_deletable' => false,
                    'is_for_all_professions' => true
                ],
                'sub_categories' => [
                    ['name' => 'Current Assets', 'code' => 5, 'type' => 2, 'industry_category_id' => [1, 2, 3]],
                    ['name' => 'Non Current Assets', 'code' => 6, 'type' => 2, 'industry_category_id' => [1, 2, 3]],
                ]
            ],
            [
                'data' => [
                    'name' => 'Liability and Equity',
                    'code' => 9,
                    'type' => 1,
                    'is_deletable' => false,
                    'is_for_all_professions' => true
                ],
                'sub_categories' => [
                    ['name' => 'Liability', 'code' => 1, 'type' => 2, 'industry_category_id' => [1, 2, 3]],
                    ['name' => 'Share Capital & Equity', 'code' => 9, 'type' => 2, 'industry_category_id' => [1, 2, 3]],
                ]
            ]
        ];

        DB::beginTransaction();
        try {
            foreach ($categories as $category) {
                $accountCodeCategory = AccountCodeCategory::create($category['data']);
                $accountCodeCategory->industryCategories()->sync([1, 2, 3]);
                foreach ($category['sub_categories'] as $sub_category) {
                    $sub_category += ['parent_id' => $accountCodeCategory->id];
                    $accountCodeSubCategory = AccountCodeCategory::create(Arr::except($sub_category, ['industry_category_id']));

                    $assignIndustryCategory->setIndustryCategory($sub_category['industry_category_id'])
                        ->setInstance($accountCodeSubCategory)
                        ->execute();
                    //unset($sub_category['industry_category_id']);
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }
}
