<?php
namespace Database\Seeders;

use App\StandardWages;
use Illuminate\Database\Seeder;

class StandardWagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $standard_wages = [
            ['id' => '1','name' => 'Basic Salary','type' => 'Salary','link_group' => '1','regular_rate' => '1.0000','hourly_rate' => '0.0000','created_at' => '2020-10-12 07:20:33','updated_at' => '2020-10-12 07:20:33'],
            ['id' => '2','name' => 'Basic Wages','type' => 'Hourly','link_group' => '1','regular_rate' => '1.0000','hourly_rate' => '0.0000','created_at' => '2020-10-12 07:21:31','updated_at' => '2020-10-12 07:21:31'],
            ['id' => '3','name' => 'BACK PAY','type' => 'Hourly','link_group' => '1','regular_rate' => '1.0000','hourly_rate' => '0.0000','created_at' => '2020-10-12 07:22:13','updated_at' => '2020-10-12 07:22:13'],
            ['id' => '4','name' => 'After Hoours(6.00 Pm)','type' => 'Hourly','link_group' => '2','regular_rate' => '0.0000','hourly_rate' => '23.0000','created_at' => '2020-10-12 07:26:10','updated_at' => '2020-10-12 07:26:22'],
            ['id' => '5','name' => 'Saterday rate(standerad)','type' => 'Hourly','link_group' => '2','regular_rate' => '0.0000','hourly_rate' => '30.0000','created_at' => '2020-10-12 07:27:00','updated_at' => '2020-10-12 07:27:00'],
            ['id' => '6','name' => 'Sunday and Public holiday','type' => 'Hourly','link_group' => '2','regular_rate' => '0.0000','hourly_rate' => '45.0000','created_at' => '2020-10-12 07:27:44','updated_at' => '2020-10-12 07:27:44'],
            ['id' => '7','name' => 'Bonous','type' => 'Salary','link_group' => '3','regular_rate' => '1.0000','hourly_rate' => '0.0000','created_at' => '2020-10-12 07:28:27','updated_at' => '2020-10-12 07:28:27'],
            ['id' => '8','name' => 'Commission sales','type' => 'Hourly','link_group' => '3','regular_rate' => '0.0000','hourly_rate' => '30.0000','created_at' => '2020-10-12 07:29:16','updated_at' => '2020-10-12 07:29:16'],
            ['id' => '9','name' => 'Director Fee','type' => 'Salary','link_group' => '5','regular_rate' => '1.0000','hourly_rate' => '0.0000','created_at' => '2020-10-12 07:30:00','updated_at' => '2020-10-12 07:30:00'],
            ['id' => '10','name' => 'Meal Allowane','type' => 'Hourly','link_group' => '4','regular_rate' => '1.0000','hourly_rate' => '0.0000','created_at' => '2020-10-12 07:30:45','updated_at' => '2020-10-12 07:30:45'],
            ['id' => '11','name' => 'Travel Allowance','type' => 'Hourly','link_group' => '4','regular_rate' => '1.0000','hourly_rate' => '0.0000','created_at' => '2020-10-12 07:31:29','updated_at' => '2020-10-12 07:31:29'],
            ['id' => '12','name' => 'Lum Sum E','type' => 'Hourly','link_group' => '6','regular_rate' => '1.0000','hourly_rate' => '0.0000','created_at' => '2020-10-12 07:32:05','updated_at' => '2020-10-12 07:32:05'],
            ['id' => '13','name' => 'Eligiable Termination Paymnet','type' => 'Hourly','link_group' => '7','regular_rate' => '1.0000','hourly_rate' => '0.0000','created_at' => '2020-10-12 07:32:46','updated_at' => '2020-10-12 07:32:46'],
            ['id' => '14','name' => 'Fringe Benefit Tax','type' => 'Hourly','link_group' => '9','regular_rate' => '1.0000','hourly_rate' => '0.0000','created_at' => '2020-10-12 07:33:35','updated_at' => '2020-10-12 07:33:35']
        ];

        StandardWages::insert($standard_wages);
    }
}
