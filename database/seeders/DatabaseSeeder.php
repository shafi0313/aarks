<?php
namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!env('IS_SEEDER_COMPLETED')) {
            $this->call(AdminSeeder::class);
            $this->call(ServiceSeeder::class);
            $this->call(StandardWagesSeeder::class);
            $this->call(IndustryCategorySeeder::class);
            $this->call(AccountCodeCategorySeeder::class);
            $this->call(ProfessionSeeder::class);
            $this->call(PermissionSeeder::class);
            Client::factory(5)->create();

            // \App\Models\User::factory(10)->create();

            // \App\Models\User::factory()->create([
            //     'name' => 'Test User',
            //     'email' => 'test@example.com',
            // ]);
        } else {
            dd("Seeder Already Completed");
        }
    }
}
