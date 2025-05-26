<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class TestCategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $records = [];

        for ($i = 1; $i <= 20; $i++) {
            $records[] = [
                'adminId'    => rand(1, 6),
                'testCat'    => $faker->words(2, true) . ' Test',
                'catDetail'  => $faker->sentence(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('test_categories')->insert($records);
    }
}
