<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class DebitTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $records = [];

        for ($i = 1; $i <= 120; $i++) {
            $records[] = [
                'userId'       => rand(1, 6),
                'debitAmount'  => $faker->randomFloat(2, 50, 1000), // Amount between 50 and 1000
                'debitDetail'  => $faker->sentence(3),
                'createdDate'  => Carbon::now()->subDays(rand(1, 60))->format('Y-m-d'),
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ];
        }

        DB::table('debit')->insert($records);
    }
}
