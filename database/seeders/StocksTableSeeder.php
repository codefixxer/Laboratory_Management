<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class StocksTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $items = ['Test Tube', 'Petri Dish', 'Microscope Slide', 'Gloves', 'Face Mask', 'Syringe', 'Alcohol Swab', 'Thermometer', 'Sample Bag', 'Vacutainer'];

        $records = [];

        for ($i = 0; $i < 100; $i++) {
            $item = $faker->randomElement($items);

            $records[] = [
                'userId'        => rand(1, 6),
                'itemName'      => $item,
                'itemDetail'    => $faker->words(3, true),
                'expDate'       => Carbon::now()->addMonths(rand(6, 24))->format('Y-m-d'),
                'itmQnt'        => rand(10, 1000),
                'itmPrice'      => rand(1, 100),
                'createdDate'   => Carbon::now()->subDays(rand(1, 60))->format('Y-m-d'),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ];
        }

        DB::table('stocks')->insert($records);
    }
}
