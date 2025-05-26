<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $records = [];

        for ($i = 1; $i <= 20; $i++) {
            $title     = $faker->randomElement(['Mr.', 'Ms.']);
            $gender    = $title === 'Mr.' ? 'Male' : 'Female';
            $extPanel  = $faker->numberBetween(1, 5);
            $referral  = $faker->numberBetween(1, 5);
            $staffPan  = $faker->numberBetween(1, 5);

            $records[] = [
                'userId'        => 5,
                'relation'      => 'self',
                'title'         => $title,
                'user_name'     => "patient{$i}",
                'name'          => $faker->name($gender),
                'email'         => "patient{$i}@example.com",
                'phone'         => $faker->phoneNumber(),
                'gender'        => $gender,
                'age'           => $faker->numberBetween(18, 75),
                'lcStatus'      => $faker->randomElement(['active', 'inactive']),
                'extPanelId'    => $extPanel,
                'addRefrealId'  => $referral,
                'staffPanelId'  => $staffPan,
                'comment'       => $faker->sentence(6),
                'testDiscount'  => $faker->randomFloat(2, 0, 20),
                'password'      => Str::random(5),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ];
        }

        DB::table('customers')->insert($records);
    }
}
