<?php


namespace App\Actions\AccountCodeActions;

use App\Actions\Creatable;
use App\Actions\BaseAction;
use Illuminate\Support\Arr;
use App\Models\ClientAccountCode;
use App\Models\ProfessionAccountCode;

class CopyClientAccountCode extends BaseAction
{
    use Creatable;
    public function __construct(ClientAccountCode $client_account_code)
    {
        $this->setModel($client_account_code);
    }

    public function execute()
    {
        $profession_account_codes = $this->getProfessionAccountCodes($this->data['profession_id']);
        foreach ($profession_account_codes->toArray() as $profession_account_code) {
            // dd($this->setData(Arr::except($profession_account_code, ['deleted_at', 'is_universal'])));

            array_push($profession_account_code, $profession_account_code['client_id'] = $this->data['client_id']);
            $this->setData(Arr::except($profession_account_code, ['deleted_at', 'is_universal']));
            $this->create();
        }
    }

    public function getProfessionAccountCodes($profession_id)
    {
        return ProfessionAccountCode::whereIn('profession_id', $profession_id)->get();
    }
}
