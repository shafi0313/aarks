<?php namespace App\Aarks;


use App\Models\AccountCode;
use App\Models\MasterAccountCode;

class CopyMasterAccountCode
{
    public static function copy()
    {
        $master_account_codes = MasterAccountCode::all();
        $account_codes = AccountCode::all();

        foreach ($master_account_codes as $master_account_code) {
            foreach ($account_codes as $account_code){
                if($master_account_code->account_code_id == $account_code->id){
                    $master_account_code->update([
                        'category_id' => $account_code->category_id,
                        'sub_category_id' => $account_code->sub_category_id,
                        'additional_category_id' => $account_code->additional_category_id,
                        'code' => $account_code->code,
                        'type' => $account_code->type,
                        'name' => $account_code->name,
                        'gst_code' => $account_code->gst_code,
                        'note' => $account_code->note,
                    ]);
                }
            }
        }
    }

}
