<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ReportsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('reports')->insert([
            ['created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
            ['created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
            ['created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
            ['created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
            ['created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
        ]);
    }
}
