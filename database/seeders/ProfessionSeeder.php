<?php
namespace Database\Seeders;

use App\Actions\ProfessionActions\AddProfession;
use App\Profession;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(AddProfession $addProfession)
    {
        $professions = [
            [
                'name' => 'Service',
                'industry_category' => [1, 2]
            ],
            [
                'name' => 'Business',
                'industry_category' => [1]
            ],
            [
                'name' =>'Doctor',
                'industry_category' => [1, 3]
            ],
        ];
        foreach ($professions as $profession) {
            $addProfession->setData($profession)->execute();
        }
    }
}
