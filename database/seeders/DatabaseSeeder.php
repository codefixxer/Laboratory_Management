<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            ExternalPanelsTableSeeder::class,
            StaffPanelsTableSeeder::class,
            TestCategoriesTableSeeder::class,
            TestsTableSeeder::class,
            ReferralsTableSeeder::class,
            CustomersTableSeeder::class,
            CustomerTestsTableSeeder::class,
            PaymentsTableSeeder::class,
            StocksTableSeeder::class,
            DebitTableSeeder::class,
            CreditTableSeeder::class,
            ReportsTableSeeder::class,
            TestreportTableSeeder::class,
            TestRangesTableSeeder::class,
            LcTableSeeder::class,
            TestreportchildTableSeeder::class,
        ]);
    }
}
