<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CreditTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('credit')->insert([
            ['userId'=>1,'creditAmount'=>500,'creditDetail'=>'Monthly grant','createdDate'=>'2025-04-01','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
            ['userId'=>2,'creditAmount'=>1000,'creditDetail'=>'Project fund','createdDate'=>'2025-04-05','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
            ['userId'=>3,'creditAmount'=>750,'creditDetail'=>'Stipend','createdDate'=>'2025-04-10','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
            ['userId'=>4,'creditAmount'=>300,'creditDetail'=>'Donation','createdDate'=>'2025-04-15','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
            ['userId'=>5,'creditAmount'=>600,'creditDetail'=>'Contract pay','createdDate'=>'2025-04-20','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
        ]);
    }
}
