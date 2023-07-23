<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Client::class, function (Faker $faker) {
    return [
        'company'           => $faker->company,
        'first_name'        => $faker->firstName,
        'last_name'         => $faker->lastName,
        'email'             => $faker->unique()->safeEmail,
        'birthday'          => $faker->dateTimeBetween(),
        'phone'             => $faker->e164PhoneNumber,
        'agent_number'      => $faker->e164PhoneNumber,
        'street_address'    => $faker->address,
        'suburb'            => $faker->address,
        'state'             => $faker->state,
        'post_code'         => $faker->postcode,
        'country'           => $faker->country,
        'agent_name'        => $faker->name,
        'auditor_name'      => $faker->name,
        'branch'            => "Brance",
        'is_gst_enabled'    => "1",
        'abn_number'        => rand(1, 15),
        'agent_abn_number'  => rand(1, 15),
        'tax_file_number'   => rand(1, 15),
        'email_verified_at' => now(),
        'password'          => bcrypt('password'),
        'remember_token'    => Str::random(10),
    ];
});
