<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TestreportTableSeeder extends Seeder
{
    public function run()
    {
        $ctIds = DB::table('customer_tests')->pluck('ctId')->toArray();
        $reporterIds = DB::table('users')->where('role', 'reporter')->pluck('id')->toArray();

        if (empty($ctIds) || empty($reporterIds)) {
            echo "❌ Seed 'customer_tests' and 'users' (reporters) first.\n";
            return;
        }

        $records = [];

        for ($i = 0; $i < 100; $i++) {
            $records[] = [
                'ctId'        => $ctIds[array_rand($ctIds)],
                'reporterId'  => $reporterIds[array_rand($reporterIds)],
                'signStatus'  => ['signed', 'pending'][rand(0, 1)],
                'createdDate' => Carbon::now()->subDays(rand(0, 30)),
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ];
        }

        DB::table('testreport')->insert($records);
    }
}
