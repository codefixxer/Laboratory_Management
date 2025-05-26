<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class LcTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $records = [];

        for ($i = 1; $i <= 120; $i++) {
            $customerIds = array_unique([
                rand(1, 30),
                rand(1, 30),
                rand(1, 30)
            ]);

            $records[] = [
                'phone_number' => $faker->unique()->phoneNumber,
                'customer_ids' => json_encode($customerIds),
                'percentage'   => $faker->randomFloat(2, 1, 10),
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ];
        }

        DB::table('lc')->insert($records);
    }
}
