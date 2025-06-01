<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use App\Models\CustomerTest;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        // Total counts for accepted and revoked reports
        $acceptedCount = CustomerTest::where('testStatus', 'accepted')->count();
        $revokedCount = CustomerTest::where('testStatus', 'revoked')->count();

        // Trends over last 30 days
        $daysAgo = Carbon::now()->subDays(29);

$acceptedTrend = CustomerTest::selectRaw('DATE(updated_at) as day, COUNT(*) as cnt')
    ->where('testStatus', 'accepted')
    ->where('updated_at', '>=', $daysAgo)
    ->groupBy('day')
    ->orderBy('day')
    ->get();

$revokedTrend = CustomerTest::selectRaw('DATE(updated_at) as day, COUNT(*) as cnt')
    ->where('testStatus', 'revoked')
    ->where('updated_at', '>=', $daysAgo)
    ->groupBy('day')
    ->orderBy('day')
    ->get();


        // X-axis for all last 30 days
        $allDays = [];
        for ($i=0; $i<30; $i++) {
            $allDays[] = Carbon::now()->subDays(29-$i)->format('Y-m-d');
        }

        $acceptedSeries = [];
        $revokedSeries = [];
        foreach ($allDays as $d) {
            $acceptedSeries[] = (int) ($acceptedTrend->firstWhere('day', $d)->cnt ?? 0);
            $revokedSeries[] = (int) ($revokedTrend->firstWhere('day', $d)->cnt ?? 0);
        }
        $chartLabels = array_map(fn($d) => Carbon::parse($d)->format('M d'), $allDays);

        $trendChart = LarapexChart::lineChart()
            ->setTitle('Accepted vs revoked Reports (30 days)')
            ->addData('Accepted', $acceptedSeries)
            ->addData('revoked', $revokedSeries)
            ->setXAxis($chartLabels)
            ->setColors(['#2ecc71','#e74c3c'])
            ->setToolbar(false);

        return view('manager.pages.dashboard', compact(
            'acceptedCount', 'revokedCount', 'trendChart'
        ));
    }
}
