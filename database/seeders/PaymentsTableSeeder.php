<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $records = [];

        for ($customerId = 1; $customerId <= 20; $customerId++) {
            // Each customer gets 1 to 3 payments
            $numPayments = rand(1, 3);

            for ($i = 0; $i < $numPayments; $i++) {
                $received = $faker->randomFloat(2, 100, 1000);
                $pending  = $faker->boolean(30) ? $faker->randomFloat(2, 50, 500) : 0;

                $records[] = [
                    'customerId'  => $customerId,
                    'recieved'    => $received,
                    'pending'     => $pending,
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ];
            }
        }

        DB::table('payments')->insert($records);
    }
}
