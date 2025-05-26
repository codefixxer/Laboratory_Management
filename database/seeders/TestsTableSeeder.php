<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class TestsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $testNames = [
            'Complete Blood Count', 'Basic Metabolic Panel', 'Urine Culture', 'Lipid Profile',
            'Thyroid Function Test', 'Liver Function Test', 'Glucose Tolerance Test', 'HbA1c',
            'Electrolyte Panel', 'Vitamin D Test', 'Calcium Test', 'Pregnancy Test',
            'CRP', 'D-Dimer', 'Ferritin', 'PSA', 'HIV Test', 'Hepatitis B', 'Widal Test', 'Stool Examination',
        ];

        $sampleTypes = [
            ['sample' => 'Blood', 'how' => '5ml blood draw'],
            ['sample' => 'Urine', 'how' => 'Midstream urine sample'],
            ['sample' => 'Stool', 'how' => 'Fresh stool container'],
            ['sample' => 'Saliva', 'how' => 'Sterile saliva cup'],
            ['sample' => 'Swab',   'how' => 'Sterile nasal/throat swab'],
        ];

        $testCatIds = DB::table('test_categories')->pluck('testCatId')->toArray();

        if (empty($testCatIds)) {
            echo "❌ Seed 'test_categories' first.\n";
            return;
        }

        $records = [];

        for ($i = 1; $i <= 100; $i++) {
            $test = $faker->randomElement($testNames) . " #" . $i;
            $sample = $faker->randomElement($sampleTypes);

            $records[] = [
                'testName'   => $test,
                'testCatId'  => $faker->randomElement($testCatIds),
                'testCost'   => $faker->randomFloat(2, 300, 3000),
                'howSample'  => $sample['how'],
                'typeSample' => $sample['sample'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('tests')->insert($records);
    }
}
