<?php namespace App\Aarks;

use App\Models\AccountCode;
use App\Models\ProfessionAccountCode;

class CopyProfessionAccountCode
{
    public static function copy()
    {
        $profession_account_codes = ProfessionAccountCode::all();
        $account_codes = AccountCode::all();

        foreach ($profession_account_codes as $profession_account_code) {
               foreach ($account_codes as $account_code){
                    if($profession_account_code->account_code_id == $account_code->id){
                        $profession_account_code->update([
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
