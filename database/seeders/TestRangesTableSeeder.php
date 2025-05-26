<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TestRangesTableSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['type' => 'Adult Male',    'gender' => 'Male',   'unit' => '10^9/L', 'min' => 4.0,  'max' => 11.0],
            ['type' => 'Adult Female',  'gender' => 'Female', 'unit' => '10^9/L', 'min' => 3.5,  'max' => 10.5],
            ['type' => 'Child',         'gender' => 'Male',   'unit' => '10^9/L', 'min' => 5.0,  'max' => 13.0],
            ['type' => 'Newborn',       'gender' => 'Any',    'unit' => '10^9/L', 'min' => 9.0,  'max' => 30.0],
            ['type' => 'Senior Male',   'gender' => 'Male',   'unit' => 'mg/dL',  'min' => 60.0, 'max' => 95.0],
            ['type' => 'Senior Female', 'gender' => 'Female', 'unit' => 'mg/dL',  'min' => 55.0, 'max' => 90.0],
            ['type' => 'Pregnant',      'gender' => 'Female', 'unit' => 'ng/mL',  'min' => 10.0, 'max' => 50.0],
            ['type' => 'General',       'gender' => null,     'unit' => 'mg/dL',  'min' => 70.0, 'max' => 99.0],
        ];

        $records = [];

        $testIds = DB::table('tests')->pluck('addTestId')->toArray();

        if (empty($testIds)) {
            echo "❌ No test records found. Seed tests first.\n";
            return;
        }

        for ($i = 0; $i < 100; $i++) {
            $testId = $testIds[array_rand($testIds)];
            $type   = $types[array_rand($types)];

            $records[] = [
                'addTestId'    => $testId,
                'testTypeName' => $type['type'],
                'gender'       => $type['gender'],
                'minRange'     => $type['min'],
                'maxRange'     => $type['max'],
                'unit'         => $type['unit'],
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ];
        }

        DB::table('test_ranges')->insert($records);
    }
}
