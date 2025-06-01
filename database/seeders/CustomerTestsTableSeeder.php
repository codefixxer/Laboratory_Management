<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CustomerTestsTableSeeder extends Seeder
{
    public function run()
    {
        $records = [];

        for ($i = 1; $i <= 40; $i++) {
            $addTestId   = rand(1, 5);   // assuming 5 tests exist
            $customerId  = rand(1, 20);  // assuming 20 customers exist
            $status      = ['pending', 'collected', 'completed'][rand(0, 2)];
            $reportDate  = $status !== 'pending' ? Carbon::now()->addDays(rand(1, 5)) : null;

            $records[] = [
                'addTestId'   => $addTestId,
                'customerId'  => $customerId,
                'created_at' => Carbon::now()->subDays(rand(0, 10)),
                'testStatus'  => $status,
                'reportDate'  => $reportDate,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ];
        }

        DB::table('customer_tests')->insert($records);
    }
}
