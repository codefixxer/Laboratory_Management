<?php
// app/Http/Controllers/Receptionist/ComparisonReportController.php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Debit;
use App\Models\Stock;
use App\Models\Credit;
use Carbon\Carbon;

class ReceptionostComparisonReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // SALES: include customer→user
        $salesRecords = Payment::with('customer.user')
            ->whereHas('customer', fn($q) => $q->where('userId', $userId))
            ->orderBy('created_at','desc')
            ->get();
        $totalSales = $salesRecords->sum('recieved');

        // EXPENSES: include user
        $debitRecords  = Debit::with('user')
            ->where('userId', $userId)->orderBy('createdDate','desc')->get();

        $stockRecords  = Stock::with('user')
            ->where('userId', $userId)->orderBy('createdDate','desc')->get();

        $creditRecords = Credit::with('user')
            ->where('userId', $userId)->orderBy('createdDate','desc')->get();

        $totalDebits    = $debitRecords->sum('debitAmount');
        $totalStockCost = $stockRecords->sum(fn($s) => $s->itmQnt * $s->itmPrice);
        $totalCredits   = $creditRecords->sum('creditAmount');

        $totalExpense = $totalDebits + $totalStockCost - $totalCredits;
        $net          = $totalSales - $totalExpense;

        // Build JS‐filterable expenses array, including added_by
        $expensesRecords = [];
        foreach ($debitRecords as $d) {
            $expensesRecords[] = [
                'type'     => 'Debit',
                'detail'   => $d->debitDetail,
                'amount'   => $d->debitAmount,
                'date'     => Carbon::parse($d->createdDate)->toDateString(),
                'added_by' => $d->user->name,
            ];
        }
        foreach ($stockRecords as $s) {
            $expensesRecords[] = [
                'type'     => 'Stock',
                'detail'   => "{$s->itemName} ({$s->itmQnt}×₹{$s->itmPrice})",
                'amount'   => $s->itmQnt * $s->itmPrice,
                'date'     => Carbon::parse($s->createdDate)->toDateString(),
                'added_by' => $s->user->name,
            ];
        }
        foreach ($creditRecords as $c) {
            $expensesRecords[] = [
                'type'     => 'Credit',
                'detail'   => $c->creditDetail,
                'amount'   => -1 * $c->creditAmount,
                'date'     => Carbon::parse($c->createdDate)->toDateString(),
                'added_by' => $c->user->name,
            ];
        }

        return view('receptionist.pages.reports.comparison', compact(
            'salesRecords',
            'expensesRecords',
            'totalSales',
            'totalDebits',
            'totalStockCost',
            'totalCredits',
            'totalExpense',
            'net'
        ));
    }
}
