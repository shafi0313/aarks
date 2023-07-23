<?php namespace App\Actions\MasterChartActions;

use App\Models\Profession;
use App\Actions\BaseAction;
use App\Models\MasterAccountCode;
use App\Actions\ProfessionActions\EditProfession;
use App\Actions\AccountCodeActions\AddProfessionAccountCode;
use App\Actions\ProfessionActions\AssignProfessionAccountCode;

class SyncMasterChart extends BaseAction
{
    private $profession;
    private $assign_profession_account_code;
    private $edit_profession;
    private $add_profession_account_code;

    public function __construct(
        AssignProfessionAccountCode $assignProfessionAccountCode,
        EditProfession $editProfession,
        AddProfessionAccountCode $add_profession_account_code
    ) {
        $this->assign_profession_account_code = $assignProfessionAccountCode;
        $this->edit_profession = $editProfession;
        $this->add_profession_account_code = $add_profession_account_code;
    }

    public function execute()
    {
//        $this->assign_profession_account_code
//            ->setInstance($this->profession)
//            ->setAccountCodes($this->getSyncableAccountCodeIds())
//            ->execute();

        foreach ($this->getSyncableAccountCodeIds() as $master_chart) {
            $data = $master_chart->only('name', 'code', 'gst_code', 'note', 'type', 'category_id', 'sub_category_id', 'additional_category_id', 'is_universal');
            $data['industry_category_id'] = $master_chart->industryCategories->pluck('id')->toArray();
            $data['code'] = substr($data['code'], 3, 3);
            $data['profession_id'] = $this->profession->id;
            $this->add_profession_account_code
                ->setData($data)
                ->execute();
        }

        $this->edit_profession
            ->setInstance($this->profession)
            ->setData([
                'name' => $this->profession->name,
                'is_master_account_code_synced' => 1,
                'can_perform_sync' => 0
            ])
            ->execute();
    }

    public function setProfession(Profession $profession)
    {
        $this->profession = $profession;
        return $this;
    }

    private function getSyncableAccountCodeIds()
    {
        $industry_categories = $this->profession->industryCategories->pluck('id');

        return MasterAccountCode::whereHas(
            'industryCategories',
            function ($query) use ($industry_categories) {
                return $query->whereIn('industry_category_id', $industry_categories);
            }
        )->with('industryCategories')->get();
    }
}
