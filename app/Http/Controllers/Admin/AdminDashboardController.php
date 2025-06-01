<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerTest;
use App\Models\Payment;
use App\Models\Debit;
use App\Models\Stock;
use App\Models\Credit;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $year = Carbon::now()->year;

        //
        // 1) Yearly Sales Overview (stacked bar)
        //
        $monthly = Payment::selectRaw("
                MONTH(created_at)   AS month,
                SUM(recieved)       AS total_received,
                SUM(pending)        AS total_pending
            ")
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months   = $monthly->pluck('month')
                            ->map(fn($m) => Carbon::create($year, $m, 1)->format('M'))
                            ->toArray();
        $received = $monthly->pluck('total_received')->toArray();
        $pending  = $monthly->pluck('total_pending')->toArray();

        $salesOverviewChart = LarapexChart::barChart()
            ->setTitle('Sales Overview')
            ->setSubtitle("$year")
            ->addData('Received', $received)
            ->addData('Pending',  $pending)
            ->setXAxis($months)
            ->setColors(['#2ecc71','#e0e0e0'])
            ->setToolbar(false);

        //
        // 2) Last-30-day Metrics
        //
        $last30 = Carbon::now()->subDays(30);
        $totalReceptionists = User::where('role','receptionist')->where('created_at','>=',$last30)->count();
        $totalManagers      = User::where('role','manager')    ->where('created_at','>=',$last30)->count();
        $totalSamplers      = User::where('role','sampler')    ->where('created_at','>=',$last30)->count();
        $totalReporters     = User::where('role','reporter')   ->where('created_at','>=',$last30)->count();
        $totalCustomers     = Customer::where('created_at','>=',$last30)->count();
        $totalCustomerTests = CustomerTest::where('created_at','>=',$last30)->count();

        //
        // 3) Sales Breakdown (mini bar)
        //
        $sumRec = Payment::sum('recieved');
        $sumPen = Payment::sum('pending');
        $salesBreakdownChart = LarapexChart::barChart()
            ->setTitle('Sales Breakdown')
            ->addData('Received', [$sumRec])
            ->addData('Pending',  [$sumPen])
            ->setXAxis([Carbon::now()->format('Y')])
            ->setColors(['#2ecc71','#e74c3c'])
            ->setToolbar(false);

        //
        // 4) Debits vs Credits (mini bar)
        //
        $sumDebits  = Debit::sum('debitAmount');
        $sumCredits = Credit::sum('creditAmount');
        $debitCreditChart = LarapexChart::barChart()
            ->setTitle('Debits vs Credits')
            ->addData('Debits',  [$sumDebits])
            ->addData('Credits', [$sumCredits])
            ->setXAxis([Carbon::now()->format('Y')])
            ->setColors(['#e67e22','#3498db'])
            ->setToolbar(false);

        //
        // 5) Expense Breakdown (mini bar)
        //
        $sumStockCost = Stock::all()->sum(fn($s)=> $s->itmQnt * $s->itmPrice);
        $expenseChart = LarapexChart::barChart()
            ->setTitle('Expense Breakdown')
            ->addData('Debits',     [$sumDebits])
            ->addData('Stock Cost', [$sumStockCost])
            ->addData('Credits',    [$sumCredits])
            ->setXAxis([Carbon::now()->format('Y')])
            ->setColors(['#e74c3c','#f1c40f','#29b6f6'])
            ->setToolbar(false);

        //
        // 6) Inventory Composition (mini bar)
        //
        $stockByItem = Stock::select('itemName', DB::raw('SUM(itmQnt) as totalQty'))
            ->groupBy('itemName')->get();
        $inventoryChart = LarapexChart::barChart()
            ->setTitle('Inventory Composition')
            ->addData('Quantity', $stockByItem->pluck('totalQty')->toArray())
            ->setXAxis($stockByItem->pluck('itemName')->toArray())
            ->setColors(['#8E44AD'])
            ->setToolbar(false);

        //
        // 7) Daily Sales (Last 30 days) → area chart
        //
        $dailyRaw = Payment::selectRaw('DATE(created_at) as day, SUM(recieved) as total')
            ->where('created_at','>=', Carbon::now()->subDays(29))
            ->groupBy('day')
            ->orderBy('day')
            ->get();
        $dailyLabels = $dailyRaw->pluck('day')
                                ->map(fn($d)=>Carbon::parse($d)->format('M d'))
                                ->toArray();
        $dailySeries = $dailyRaw->pluck('total')->toArray();

        $dailySalesAreaChart = LarapexChart::areaChart()
            ->setTitle('Daily Sales (30d)')
            ->addData('Received', $dailySeries)
            ->setXAxis($dailyLabels)
            ->setColors(['#2ecc71'])
            ->setToolbar(false);

        //
        // 8) Tests by Category → pie chart
        //
        $catCounts = CustomerTest::join('tests','customer_tests.addTestId','=','tests.addTestId')
            ->select('tests.testName as name', DB::raw('count(*) as cnt'))
            ->groupBy('tests.testName')
            ->orderByDesc('cnt')
            ->get();
    // 8) Tests by Category → pie chart
$catCounts = CustomerTest::join('tests','customer_tests.addTestId','=','tests.addTestId')
    ->select('tests.testName as name', DB::raw('count(*) as cnt'))
    ->groupBy('tests.testName')
    ->orderByDesc('cnt')
    ->get();

$testCategoryPieChart = LarapexChart::pieChart()
    ->setTitle('Tests by Category')
    ->addData($catCounts->pluck('cnt')->toArray())
    ->setLabels($catCounts->pluck('name')->toArray());


        //
        // 9) Monthly Debits vs Credits → area chart
        //
        $monthDebits  = Debit::selectRaw('MONTH(created_at) m, SUM(debitAmount) as amt')
            ->whereYear('created_at',$year)
            ->groupBy('m')->pluck('amt','m')->toArray();
        $monthCredits = Credit::selectRaw('MONTH(created_at) m, SUM(creditAmount) as amt')
            ->whereYear('created_at',$year)
            ->groupBy('m')->pluck('amt','m')->toArray();
        $allMonths     = range(1,12);
        $areaLabels    = array_map(fn($m)=>Carbon::create($year,$m,1)->format('M'), $allMonths);
        $debitSeries   = array_map(fn($m)=> $monthDebits[$m] ?? 0, $allMonths);
        $creditSeries  = array_map(fn($m)=> $monthCredits[$m] ?? 0, $allMonths);

        $debitCreditAreaChart = LarapexChart::areaChart()
            ->setTitle("Debits vs Credits ($year)")
            ->addData('Debits',  $debitSeries)
            ->addData('Credits', $creditSeries)
            ->setXAxis($areaLabels)
            ->setColors(['#e67e22','#3498db'])
            ->setToolbar(false);

        return view('admin.pages.dashboard', compact(
            'salesOverviewChart',
            'totalReceptionists','totalManagers','totalSamplers',
            'totalReporters','totalCustomers','totalCustomerTests',
            'salesBreakdownChart','debitCreditChart','expenseChart','inventoryChart',
            'dailySalesAreaChart','testCategoryPieChart','debitCreditAreaChart'
        ));
    }
}
