<?php

namespace App\Imports;

use App\Import;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BSImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Import([
            'account_code'     => $row['account_number'],
            'date'    => date('Y-m-d', strtotime(str_replace('-', '/', $row['transaction_date']))),
            'narration' => $row['narration'],
            'debit' => empty($row['debit'])?0.00:$row['debit'],
            'credit' => empty($row['credit'])?0.00:$row['credit'],
        ]);
    }

}
