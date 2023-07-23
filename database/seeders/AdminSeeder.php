<?php
namespace Database\Seeders;

use App\Admin;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $admin_info = [
            [
                'name' => 'Admin',
                'email' => 'admin@aarks.net.au',
                'email_verified_at' => $now,
                'password' => bcrypt('password'),
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Anwar Hossain',
                'email' => 'anwar@aarks.net.au',
                'email_verified_at' => $now,
                'password' => bcrypt('password'),
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];
        Admin::insert($admin_info);
    }
}
