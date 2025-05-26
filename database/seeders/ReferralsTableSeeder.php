<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ReferralsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('referrals')->insert([
            ['referrerName'=>'Dr. Smith','referrerDetails'=>'GP referral','commissionPercentage'=>5,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
            ['referrerName'=>'Clinic ABC','referrerDetails'=>'Outpatient center','commissionPercentage'=>7.5,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
            ['referrerName'=>'Hospital XYZ','referrerDetails'=>'In-house referrals','commissionPercentage'=>10,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
            ['referrerName'=>'Lab Corp','referrerDetails'=>'Partner lab','commissionPercentage'=>4,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
            ['referrerName'=>'HealthPlus','referrerDetails'=>'Online portal','commissionPercentage'=>6,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
        ]);
    }
}
