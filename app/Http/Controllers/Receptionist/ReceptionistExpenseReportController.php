<?php
namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Debit;
use App\Models\Stock;
use Carbon\Carbon;

class ReceptionistExpenseReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Only the debits this receptionist created
        $debits = Debit::with('user')
            ->where('userId', $userId)
            ->orderBy('createdDate', 'desc')
            ->get();

        // Only the stocks this receptionist added
        $stocks = Stock::with('user')
            ->where('userId', $userId)
            ->orderBy('createdDate', 'desc')
            ->get();

        // Totals
        $totalDebits    = $debits->sum('debitAmount');
        $totalStockCost = $stocks->sum(fn($s) => $s->itmQnt * $s->itmPrice);
        $totalExpense   = $totalDebits + $totalStockCost;

        return view('receptionist.pages.reports.expenses', compact(
            'debits', 'stocks',
            'totalDebits', 'totalStockCost', 'totalExpense'
        ));
    }
}
