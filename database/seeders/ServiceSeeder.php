<?php
namespace Database\Seeders;

use App\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            ['name' => 'Company services', 'status' => true],
            ['name' => 'Fuel Tax Credit', 'status' => true],
            ['name' => 'Individual Business Activity Statement(BAS)', 'status' => true],
            ['name' => 'Individual Tax Return', 'status' => true],
            ['name' => 'Installment Activity Statement', 'status' => true],
            ['name' => 'PAYROLL', 'status' => true],
            ['name' => 'Preparing Invoice', 'status' => true]
        ];
        Service::insert($services);


    }
}
