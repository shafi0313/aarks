<?php

namespace App\Imports;

use App\Coefficient;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class CoefficientImport implements ToModel, WithHeadingRow
{
    use Importable;
    public function __construct($holding_type)
    {
        $this->holding_type = $holding_type;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Coefficient([
            'holding_type'   => $this->holding_type,
            'weekly_earning' => $row['weekly_earning'],
            'per_dollar_a'   => $row['per_dollar_a'],
            'per_dollar_b'   => $row['per_dollar_b'],
            'year'           => $row['year']
        ]);
    }
}
