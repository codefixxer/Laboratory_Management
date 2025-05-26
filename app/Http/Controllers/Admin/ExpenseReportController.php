<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Debit;
use App\Models\Stock;
use Carbon\Carbon;

class ExpenseReportController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all debits (with user) and stocks
        $debits = Debit::with('user')
                       ->orderBy('createdDate', 'desc')
                       ->get();

        $stocks = Stock::orderBy('createdDate', 'desc')
                       ->get();

        // Totals
        $totalDebits      = $debits->sum('debitAmount');
        $totalStockCost   = $stocks->sum(fn($s) => $s->itmQnt * $s->itmPrice);
        $totalExpense     = $totalDebits + $totalStockCost;

        return view('admin.pages.reports.expenses', compact(
            'debits', 'stocks',
            'totalDebits', 'totalStockCost', 'totalExpense'
        ));
    }
}
