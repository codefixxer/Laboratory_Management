<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TestreportchildTableSeeder extends Seeder
{
    public function run()
    {
        $reportIds = DB::table('testreport')->pluck('reportId')->toArray();
        $testRanges = DB::table('test_ranges')->get();

        if (empty($reportIds) || $testRanges->isEmpty()) {
            echo "❌ Seed 'testreport' and 'test_ranges' first.\n";
            return;
        }

        $records = [];

        for ($i = 0; $i < 60; $i++) {
            $reportId = $reportIds[array_rand($reportIds)];
            $range = $testRanges->random();

            // Generate value within range, or 0 if range is undefined
            if ($range->minRange !== null && $range->maxRange !== null && $range->minRange < $range->maxRange) {
                $value = round(mt_rand($range->minRange * 100, $range->maxRange * 100) / 100, 2);
            } else {
                $value = '0.0';
            }

            $records[] = [
                'reportId'     => $reportId,
                'testRangeId'  => $range->testRangeId,
                'reportValue'  => (string) $value,
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ];
        }

        DB::table('testreportchild')->insert($records);
    }
}
