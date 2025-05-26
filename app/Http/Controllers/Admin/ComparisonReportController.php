<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Debit;
use App\Models\Stock;
use App\Models\Credit;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ComparisonReportController extends Controller
{
    public function index(Request $request)
    {
        // Gather filter inputs
        $search = $request->input('search');
        $from   = $request->input('from');
        $to     = $request->input('to');
        $minAmt = $request->input('min');
        $maxAmt = $request->input('max');

        // **Sales** query
        $salesQuery = Payment::with('customer');
        if ($search) {
            $salesQuery->whereHas('customer', fn($q) => 
                $q->where('name','like',"%{$search}%")
            )->orWhere('recieved','like',"%{$search}%");
        }
        if ($from) $salesQuery->whereDate('created_at','>=',$from);
        if ($to)   $salesQuery->whereDate('created_at','<=',$to);
        if ($minAmt) $salesQuery->where('recieved','>=',$minAmt);
        if ($maxAmt) $salesQuery->where('recieved','<=',$maxAmt);

        $salesPaginator = $salesQuery
            ->orderBy('created_at','desc')
            ->paginate(10, ['*'], 'sales_page')
            ->appends($request->except('sales_page','expenses_page'));

        $totalSales = $salesQuery->sum('recieved');

        // **Expense** summary
        $totalDebits = Debit::sum('debitAmount');
        $stockItems  = Stock::all();
        $totalStockCost = $stockItems->sum(fn($s) => $s->itmQnt * $s->itmPrice);
        $totalCredits = Credit::sum('creditAmount');
        $totalExpense = $totalDebits + $totalStockCost - $totalCredits;
        $net          = $totalSales - $totalExpense;

        // Build unified expenses collection
        $allExpenses = new Collection();

        Debit::with('user')->get()->each(fn($d) => $allExpenses->push([
            'type'   => 'Debit',
            'user'   => $d->user->name,
            'detail' => $d->debitDetail,
            'amount' => $d->debitAmount,
            'date'   => Carbon::parse($d->createdDate)->toDateString(),
        ]));

        Stock::all()->each(fn($s) => $allExpenses->push([
            'type'   => 'Stock',
            'user'   => '-',
            'detail' => "{$s->itemName} ({$s->itmQnt}×₹{$s->itmPrice})",
            'amount' => $s->itmQnt * $s->itmPrice,
            'date'   => Carbon::parse($s->createdDate)->toDateString(),
        ]));

        Credit::with('user')->get()->each(fn($c) => $allExpenses->push([
            'type'   => 'Credit',
            'user'   => $c->user->name,
            'detail' => $c->creditDetail,
            'amount' => -1 * $c->creditAmount,
            'date'   => Carbon::parse($c->createdDate)->toDateString(),
        ]));

        // Apply same filters on the expense collection
        $filteredExpenses = $allExpenses->filter(function($e) use ($search, $from, $to, $minAmt, $maxAmt) {
            $txt  = strtolower($e['type'].' '.$e['detail'].' '.$e['user']);
            $amt  = abs($e['amount']);
            $okSearch = !$search || str_contains($txt, strtolower($search)) || str_contains($e['date'], $search);
            $okDate   = (!$from || $e['date'] >= $from) && (!$to || $e['date'] <= $to);
            $okMin    = !$minAmt || $amt >= $minAmt;
            $okMax    = !$maxAmt || $amt <= $maxAmt;
            return $okSearch && $okDate && $okMin && $okMax;
        })->sortByDesc('date')->values();

        // Paginate expenses manually
        $page    = LengthAwarePaginator::resolveCurrentPage('expenses_page');
        $perPage = 10;
        $expSlice= $filteredExpenses->slice(($page-1)*$perPage, $perPage);
        $expensesPaginator = new LengthAwarePaginator(
            $expSlice,
            $filteredExpenses->count(),
            $perPage,
            $page,
            [
                'path'     => route('admin.comparison_report'),
                'pageName' => 'expenses_page',
            ]
        );
        $expensesPaginator->appends($request->except('sales_page','expenses_page'));

        return view('admin.pages.reports.comparison', [
            'salesPaginator'   => $salesPaginator,
            'expensesPaginator'=> $expensesPaginator,
            'totalSales'       => $totalSales,
            'totalDebits'      => $totalDebits,
            'totalStockCost'   => $totalStockCost,
            'totalCredits'     => $totalCredits,
            'totalExpense'     => $totalExpense,
            'net'              => $net,
        ]);
    }
}
