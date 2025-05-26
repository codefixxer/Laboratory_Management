<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['customer.receptionist']);

        // … your existing date‐range filtering …

        $payments = $query->orderBy('created_at', 'desc')->get();

        $totalReceived = $payments->sum('recieved');
        $totalPending  = $payments->sum('pending');

        $monthly = $query
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month")
            ->selectRaw("SUM(recieved) as total_received, SUM(pending) as total_pending")
            ->groupBy('month')
            ->orderBy('month','desc')
            ->get();

        return view('admin.pages.reports.sales', compact(
            'payments','totalReceived','totalPending','monthly'
        ));
    }
}
