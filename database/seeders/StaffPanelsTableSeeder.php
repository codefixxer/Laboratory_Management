<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class StaffPanelsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $records = [];

        for ($i = 1; $i <= 40; $i++) {
            $credits = $faker->randomFloat(2, 100, 2000);
            $remainingCredits = $faker->randomFloat(2, 0, $credits);

            $records[] = [
                'userId'           => rand(1, 6),
                'credits'          => $credits,
                'remainingCredits' => $remainingCredits,
                'createdDate'      => Carbon::now()->subDays(rand(1, 90))->format('Y-m-d'),
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ];
        }

        DB::table('staff_panels')->insert($records);
    }
}
