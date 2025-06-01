<?php

namespace App\Http\Controllers\Reporter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use App\Models\CustomerTest;

class ReporterDashboardController extends Controller
{
    public function index()
    {
        // Count of tests ready for report
        $sampledCount = CustomerTest::where('testStatus', 'collected')->count();

        // Count of revoked tests
        $revokedCount = CustomerTest::where('testStatus', 'revoked')->count();

        // Daily trends for both types (last 30 days)
        $daysAgo = Carbon::now()->subDays(29);

        $collectedTrend = CustomerTest::selectRaw('DATE(created_at) as day, COUNT(*) as cnt')
            ->where('testStatus', 'collected')
            ->where('created_at', '>=', $daysAgo)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $revokedTrend = CustomerTest::selectRaw('DATE(created_at) as day, COUNT(*) as cnt')
            ->where('testStatus', 'revoked')
            ->where('created_at', '>=', $daysAgo)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Create unified X-axis (all last 30 days)
        $allDays = [];
        for ($i=0; $i<30; $i++) {
            $allDays[] = Carbon::now()->subDays(29-$i)->format('Y-m-d');
        }

        // Map counts to each day (fill 0s if none)
        $collectedSeries = [];
        $revokedSeries = [];
        foreach ($allDays as $d) {
            $collectedSeries[] = (int) ($collectedTrend->firstWhere('day', $d)->cnt ?? 0);
            $revokedSeries[]   = (int) ($revokedTrend->firstWhere('day', $d)->cnt ?? 0);
        }
        $chartLabels = array_map(fn($d) => Carbon::parse($d)->format('M d'), $allDays);

        $trendChart = LarapexChart::lineChart()
            ->setTitle('Sampled vs Revoked (30 days)')
            ->addData('Sampled', $collectedSeries)
            ->addData('Revoked', $revokedSeries)
            ->setXAxis($chartLabels)
            ->setColors(['#2ecc71','#e74c3c'])
            ->setToolbar(false);

        return view('reporter.pages.dashboard', compact(
            'sampledCount', 'revokedCount', 'trendChart'
        ));
    }
}
