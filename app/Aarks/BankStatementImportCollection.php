<?php

namespace App\Aarks;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\BankStatementImport;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BankStatementImportCollection implements ToCollection, WithHeadingRow
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

    public function tempSolution($client, $profession)
    {
        foreach ($this->data as $row) {
            $date = Carbon::createFromFormat(aarks('frontend_date_format'), $row['transaction_date']);
            if (periodLock($client, ($date))) {
                Alert::error('Your enter data period is locked, check administration');
                return back();
            }
            if($row['debit'] < 0 || $row['credit'] < 0){
                Alert::error('Please enter positive value for debit and credit');
                return back();
            }
            try {
                $datum = BankStatementImport::create([
                    'date'          => $date->format(aarks('backend_date_format')),
                    'narration'     => $row['narration'],
                    'debit'         => empty($row['debit']) ? 0.00 : $row['debit'],
                    'credit'        => empty($row['credit']) ? 0.00 : $row['credit'],
                    'client_id'     => $client,
                    'profession_id' => $profession,
                ]);
                if ($datum->count() > 0) {
                    Alert::success('Success', 'Data Inserted Successfully!');
                } else {
                    Alert::error('Error', 'Entered file is empty or invalid file format!');
                }
            } catch (\Exception $exception) {
                Alert::error('Error', 'Insertion Error!');
            }
        }
    }
}
