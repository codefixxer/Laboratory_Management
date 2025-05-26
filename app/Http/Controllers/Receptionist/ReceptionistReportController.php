<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReceptionistReportController extends Controller
{
    public function index(Request $request)
    {
        $receptionistId = Auth::id();

        // Only payments whose customer.userId matches the logged-in receptionist
        $query = Payment::whereHas('customer', function($q) use ($receptionistId) {
            $q->where('userId', $receptionistId);
        });

        // Optional date-range filtering on created_at (still server-side, to seed initial data)
        if ($request->filled('from') && $request->filled('to')) {
            $from = Carbon::parse($request->from)->startOfDay();
            $to   = Carbon::parse($request->to)->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
        }

        $payments      = $query->orderBy('created_at', 'desc')->get();
        $totalReceived = $payments->sum('recieved');
        $totalPending  = $payments->sum('pending');

        $monthly = $query
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month")
            ->selectRaw("SUM(recieved) as total_received, SUM(pending) as total_pending")
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        return view('receptionist.pages.reports.sales', compact(
            'payments', 'totalReceived', 'totalPending', 'monthly'
        ));
    }
}
