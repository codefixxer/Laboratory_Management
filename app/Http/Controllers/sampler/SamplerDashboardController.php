<?php

namespace App\Http\Controllers\Sampler;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use App\Models\CustomerTest;

class SamplerDashboardController extends Controller
{
    public function index()
    {
        // All customer tests with testStatus 'pending'
        $pendingCount = CustomerTest::where('testStatus', 'pending')->count();

        // All customer tests with testStatus 'collected'
        $collectedCount = CustomerTest::where('testStatus', 'collected')->count();

        // Daily trend of collected samples (last 30 days, whole lab)
        $dailyCollected = CustomerTest::selectRaw('DATE(created_at) as day, COUNT(*) as cnt')
            ->where('testStatus', 'collected')
            ->where('created_at', '>=', Carbon::now()->subDays(29))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $chartLabels = $dailyCollected->pluck('day')
            ->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray();
        $chartSeries = $dailyCollected->pluck('cnt')->toArray();

        $collectedLineChart = LarapexChart::lineChart()
            ->setTitle('Samples Collected (30 days)')
            ->addData('Collected', $chartSeries)
            ->setXAxis($chartLabels)
            ->setColors(['#2ecc71'])
            ->setToolbar(false);

        return view('sampler.pages.dashboard', compact(
            'pendingCount', 'collectedCount', 'collectedLineChart'
        ));
    }
}
