<?php
namespace Database\Seeders;

use App\IndustryCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IndustryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $default_industry_categories = [
            ['name' => 'Manufacturing', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Trading', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Services', 'created_at' => $now, 'updated_at' => $now]
        ];

        IndustryCategory::insert($default_industry_categories);
    }
}
