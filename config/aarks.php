<?php
return[
    'gst_code' => [
        'Nil','GST','INP','FRE','FOA','CAP','ADS','ITS','W1','W2','SUP'
    ],
    'asset_version' => [
        'aarks_js' => '1.0.1',
    ],
    'default_asset_version' => '1.0.0',
    'backend_date_format'   => 'Y-m-d',
    'frontend_date_format'  => 'd/m/Y',
    'liquid_asset_id'       => env('LIQUID_ASSET_ID'),
    'js_date_format'        => "dd/mm/yy",

    'general_ledger_transaction_for' => [
        'gst'      => 'GST',
        'main'     => 'MAIN',
        'opposite' => 'OPPOSITE'
    ],
    'manoar'=>'Tarik Manoar',
    'financial_month' => [
        'July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March', 'April', 'May', 'June'
    ],
];
