<?php namespace App\Aarks;

use App\Models\Coefficient;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use RealRashid\SweetAlert\Facades\Alert;

class CoefficientImportCollection implements ToCollection,WithHeadingRow
{
    public $data;

    /**
     * @inheritDoc
     */
    public function collection(Collection $collection)
    {
        $this->data = $collection;
        return $this;
    }

    public function tempSolution($holding_type)
    {
        foreach ($this->data as $row)
        {
            try {
                Coefficient::create([
                'holding_type'   => $holding_type,
                'weekly_earning' => $row['weekly_earning'],
                'per_dollar_a'   => $row['per_dollar_a'],
                'per_dollar_b'   => $row['per_dollar_b'],
                'year'           => $row['year']
            ]);
            }catch (\Exception $exception){
                Alert::error('Error','Insertion Error!');
            }
        }
    }

}
