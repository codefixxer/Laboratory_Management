<?php

namespace App\Http\Controllers\Receptionist;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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

class ReceptionistDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $year   = Carbon::now()->year;

        //
        // 1) Yearly “My Sales Overview” (stacked bar):
        //
        $monthly = Payment::selectRaw("
                MONTH(created_at)   AS month,
                SUM(recieved)       AS total_received,
                SUM(pending)        AS total_pending
            ")
            ->whereYear('created_at', $year)
            ->whereHas('customer', fn($q) => $q->where('userId', $userId))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months    = $monthly->pluck('month')
                             ->map(fn($m) => Carbon::create($year, $m, 1)->format('M'))
                             ->toArray();
        $received  = $monthly->pluck('total_received')->toArray();
        $pending   = $monthly->pluck('total_pending')->toArray();

        $salesOverviewChart = LarapexChart::barChart()
            ->setTitle('My Sales Overview')
            ->setSubtitle("$year")
            ->addData('Received', $received)
            ->addData('Pending',  $pending)
            ->setXAxis($months)
            ->setColors(['#2ecc71','#e0e0e0'])
            ->setToolbar(false);

        //
        // 2) Last‐30‐day metrics (counts):
        //
        $last30 = Carbon::now()->subDays(30);

        $totalCustomers = Customer::where('userId', $userId)
                                  ->where('created_at', '>=', $last30)
                                  ->count();

        $totalCustomerTests = CustomerTest::join('customers','customer_tests.customerId','=', 'customers.customerId')
            ->where('customers.userId', $userId)
            ->where('customer_tests.created_at', '>=', $last30)
            ->count();

        $totalPayments   = Payment::whereHas('customer', fn($q) => $q->where('userId', $userId))
                                  ->where('created_at', '>=', $last30)
                                  ->count();

        $totalDebits     = Debit::where('userId', $userId)
                                 ->where('created_at', '>=', $last30)
                                 ->count();

        $totalCredits    = Credit::where('userId', $userId)
                                  ->where('created_at', '>=', $last30)
                                  ->count();

        $totalStocks     = Stock::where('userId', $userId)
                                 ->where('created_at', '>=', $last30)
                                 ->count();

        //
        // 3) “My Sales Breakdown” (mini bar) — total received vs pending:
        //
        $sumRec = Payment::whereHas('customer', fn($q) => $q->where('userId', $userId))->sum('recieved');
        $sumPen = Payment::whereHas('customer', fn($q) => $q->where('userId', $userId))->sum('pending');

        $salesBreakdownChart = LarapexChart::barChart()
            ->setTitle('My Sales Breakdown')
            ->addData('Received', [$sumRec])
            ->addData('Pending',  [$sumPen])
            ->setXAxis([Carbon::now()->format('Y')])
            ->setColors(['#2ecc71','#e74c3c'])
            ->setToolbar(false);

        //
        // 4) “My Debits vs Credits” (mini bar):
        //
        $sumDebitsUser  = Debit::where('userId', $userId)->sum('debitAmount');
        $sumCreditsUser = Credit::where('userId', $userId)->sum('creditAmount');

        $debitCreditChart = LarapexChart::barChart()
            ->setTitle('My Debits vs Credits')
            ->addData('Debits',  [$sumDebitsUser])
            ->addData('Credits', [$sumCreditsUser])
            ->setXAxis([Carbon::now()->format('Y')])
            ->setColors(['#e67e22','#3498db'])
            ->setToolbar(false);

        //
        // 5) “My Expense Breakdown” (mini bar): Debits, Stock Cost, Credits:
        //
        $sumStockCostUser = Stock::where('userId', $userId)
                                 ->get()
                                 ->sum(fn($s) => $s->itmQnt * $s->itmPrice);

        $expenseChart = LarapexChart::barChart()
            ->setTitle('My Expense Breakdown')
            ->addData('Debits',     [$sumDebitsUser])
            ->addData('Stock Cost', [$sumStockCostUser])
            ->addData('Credits',    [$sumCreditsUser])
            ->setXAxis([Carbon::now()->format('Y')])
            ->setColors(['#e74c3c','#f1c40f','#29b6f6'])
            ->setToolbar(false);

        //
        // 6) “My Inventory Composition” (mini bar):
        //
        $stockByItem = Stock::select('itemName', DB::raw('SUM(itmQnt) as totalQty'))
            ->where('userId', $userId)
            ->groupBy('itemName')
            ->get();

        $inventoryChart = LarapexChart::barChart()
            ->setTitle('My Inventory Composition')
            ->addData('Quantity', $stockByItem->pluck('totalQty')->toArray())
            ->setXAxis($stockByItem->pluck('itemName')->toArray())
            ->setColors(['#8E44AD'])
            ->setToolbar(false);

        //
        // 7) “My Daily Sales (Last 30 days)” → area chart:
        //
        $dailyRaw = Payment::selectRaw('DATE(created_at) as day, SUM(recieved) as total')
            ->whereHas('customer', fn($q) => $q->where('userId', $userId))
            ->where('created_at','>=', Carbon::now()->subDays(29))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $dailyLabels = $dailyRaw->pluck('day')
                                ->map(fn($d) => Carbon::parse($d)->format('M d'))
                                ->toArray();
        $dailySeries = $dailyRaw->pluck('total')->toArray();

        $dailySalesAreaChart = LarapexChart::areaChart()
            ->setTitle('My Daily Sales (30d)')
            ->addData('Received', $dailySeries)
            ->setXAxis($dailyLabels)
            ->setColors(['#2ecc71'])
            ->setToolbar(false);

        //
        // 8) “My Tests by Category” → pie chart:
        //
        $catCounts = CustomerTest::join('customers','customer_tests.customerId','=','customers.customerId')
            ->join('tests','customer_tests.addTestId','=','tests.addTestId')
            ->where('customers.userId', $userId)
            ->select('tests.testName as name', DB::raw('count(*) as cnt'))
            ->groupBy('tests.testName')
            ->orderByDesc('cnt')
            ->get();

        $testCategoryPieChart = LarapexChart::pieChart()
            ->setTitle('My Tests by Category')
            ->addData($catCounts->pluck('cnt')->toArray())
            ->setLabels($catCounts->pluck('name')->toArray());

        //
        // 9) “My Monthly Debits vs Credits” → area chart:
        //
        $monthDebitsUser  = Debit::selectRaw('MONTH(created_at) m, SUM(debitAmount) as amt')
            ->whereYear('created_at',$year)
            ->where('userId', $userId)
            ->groupBy('m')
            ->pluck('amt','m')
            ->toArray();

        $monthCreditsUser = Credit::selectRaw('MONTH(created_at) m, SUM(creditAmount) as amt')
            ->whereYear('created_at',$year)
            ->where('userId', $userId)
            ->groupBy('m')
            ->pluck('amt','m')
            ->toArray();

        $allMonths     = range(1,12);
        $areaLabels    = array_map(fn($m) => Carbon::create($year,$m,1)->format('M'), $allMonths);
        $debitSeries   = array_map(fn($m) => $monthDebitsUser[$m] ?? 0, $allMonths);
        $creditSeries  = array_map(fn($m) => $monthCreditsUser[$m] ?? 0, $allMonths);

        $debitCreditAreaChart = LarapexChart::areaChart()
            ->setTitle("My Debits vs Credits ($year)")
            ->addData('Debits',  $debitSeries)
            ->addData('Credits', $creditSeries)
            ->setXAxis($areaLabels)
            ->setColors(['#e67e22','#3498db'])
            ->setToolbar(false);

        return view('receptionist.pages.dashboard', compact(
            'salesOverviewChart','userId',
            'totalCustomers','totalCustomerTests','totalPayments','totalDebits','totalCredits','totalStocks',
            'salesBreakdownChart','debitCreditChart','expenseChart','inventoryChart',
            'dailySalesAreaChart','testCategoryPieChart','debitCreditAreaChart'
        ));
    }
}
