<?php namespace App\Actions\AccountCodeActions;

use App\Actions\Creatable;
use App\Actions\BaseAction;
use Illuminate\Support\Arr;
use App\Models\ClientAccountCode;
use App\Actions\CreateAccountCode;
use App\Actions\SharedActions\AssignIndustryCategory;

class AddClientAccountCode extends BaseAction
{
    use Creatable;
    use CreateAccountCode;

    protected $assign_industry_category;

    public function __construct(ClientAccountCode $client_account_code, AssignIndustryCategory $assignIndustryCategory)
    {
        $this->setModel($client_account_code);
        $this->assign_industry_category = $assignIndustryCategory;
    }

    public function execute()
    {
        $account_code = $this->create();

        // $this->assign_industry_category
        //     ->setInstance($account_code)
        //     ->setIndustryCategory($this->data['industry_category_id'])
        //     ->execute();

        return $account_code;
    }

    protected function data()
    {
        $data = Arr::except($this->data, ['industry_category_id']);
        $data['code'] = $this->generateAccountCode();
        $this->checkAccountCodeDuplication($data['code']);
        return $data;
    }

    private function checkAccountCodeDuplication($account_code)
    {
        $existing_account_code = $this->getModel()
            ->where('code', $account_code)
            ->where('client_id', $this->data['client_id'])
            ->where('profession_id', $this->data['profession_id'])
            ->count();

        if ($existing_account_code) {
            throw new \Exception("Account Code Already Exist for This Profession");
        }
    }
}
