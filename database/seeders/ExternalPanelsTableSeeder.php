<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ExternalPanelsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('external_panels')->insert([
            [
                'panelName'        => 'Panel A',
                'panelAddrs'       => '123 Main St',
                'credits'          => 1000.00,
                'remainingCredits' => 800.00,
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
            [
                'panelName'        => 'Panel B',
                'panelAddrs'       => '456 Elm St',
                'credits'          => 2000.00,
                'remainingCredits' => 1500.00,
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
            [
                'panelName'        => 'Panel C',
                'panelAddrs'       => '789 Oak St',
                'credits'          => 1500.00,
                'remainingCredits' => 1200.00,
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
            [
                'panelName'        => 'Panel D',
                'panelAddrs'       => '101 Pine St',
                'credits'          => 500.00,
                'remainingCredits' => 200.00,
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
            [
                'panelName'        => 'Panel E',
                'panelAddrs'       => '202 Maple St',
                'credits'          => 1200.00,
                'remainingCredits' => 1100.00,
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
        ]);
    }
}
