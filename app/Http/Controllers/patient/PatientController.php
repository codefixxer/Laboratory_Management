<?php

namespace App\Http\Controllers\Patient;

use App\Models\Report;
use App\Models\Customer;
// use Barryvdh\DomPDF\PDF;
use App\Models\TestRange;
use App\Models\TestReport;
use App\Models\CustomerTest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Use this facade!
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use PDF; 

class PatientController extends Controller
{


public function downloadReport($reportId)
{

    // dd($reportId);
    
    // Fetch the report with all relations
    $report = TestReport::with([
        'customerTest.customer',
        'customerTest.test',
        'reportChildren.relatedTestRange'
    ])->findOrFail($reportId);

    // Security: Only allow if the session customer is the report's owner!
    $customerId = session('customerId');
    if ($customerId !== ($report->customerTest->customer->customerId ?? null)) {
        abort(403, 'Unauthorized access to this report.');
    }

    $customer = $report->customerTest->customer;

    $pdf = Pdf::loadView('patient.pages.report-pdf', compact('report', 'customer'))
        ->setPaper('a4', 'portrait');

    return $pdf->download('medical-report-' . $report->reportId . '.pdf');
}

   
   
   
   
   
   
public function dashboard()
{
    $customerId = session('customerId');
    if (!$customerId) {
        return redirect()->route('patient.login')->withErrors(['login_id' => 'Session expired. Please login again.']);
    }

    // Accepted reports (matches logic of completedReports)
    $acceptedReports = \App\Models\CustomerTest::where('customerId', $customerId)
        ->where('testStatus', 'accepted')
        ->count();

    // Pending reports (matches logic of pendingReports)
    $pendingReports = \App\Models\CustomerTest::where('customerId', $customerId)
        ->where('testStatus', '!=', 'accepted')
        ->count();

    $totalReports = $acceptedReports + $pendingReports;

    // For graph, same data structure as before
    $graphData = [
        'Total'    => $totalReports,
        'Pending'  => $pendingReports,
        'Accepted' => $acceptedReports,
    ];

    // Get the logged-in customer for personalization
    $customer = \App\Models\Customer::find($customerId);

    return view('patient.pages.dashboard', compact(
        'totalReports',
        'pendingReports',
        'acceptedReports',
        'graphData',
        'customer'
    ));
}










    public function pendingReports()
    {
        $customerId = session('customerId');
        if (!$customerId) {
            return redirect()->route('patient.login')->withErrors(['login_id' => 'Session expired. Please login again.']);
        }

        // Get only tests with status not 'accepted'
        $pendingTests = \App\Models\CustomerTest::where('customerId', $customerId)
            ->where('testStatus', '!=', 'accepted')
            ->with(['test'])
            ->orderByDesc('created_at')
            ->get();

        return view('patient.pages.pending', compact('pendingTests'));
    }



  public function completedReports()
{
    $customerId = session('customerId');
    if (!$customerId) {
        return redirect()->route('patient.login')->withErrors(['login_id' => 'Session expired. Please login again.']);
    }

    // Get all accepted tests for this customer
    $completedTests = \App\Models\CustomerTest::where('customerId', $customerId)
        ->where('testStatus', 'accepted')
        ->with(['test', 'customer.payments'])
        ->orderByDesc('created_at')
        ->get();

    // Sum all pending amounts for this customer
    $totalPending = 0;
    if ($completedTests->first() && $completedTests->first()->customer) {
        $totalPending = $completedTests->first()->customer->payments->sum('pending');
    }

    return view('patient.pages.completed', compact('completedTests', 'totalPending'));
}


    // public function completedReports()
    // {
    //     $customerId = session('customerId');

    //     // Retrieve completed test reports
    //     $completedReports = TestReport::whereHas('customerTest', function ($query) use ($customerId) {
    //         $query->where('customerId', $customerId)
    //               ->where('testStatus', 'accepted'); // Test must be accepted
    //     })->where('signStatus', 'accepted') // Report must be signed as accepted
    //     ->whereHas('customerTest.customer.payment', function ($query) use ($customerId) {
    //         $query->where('customerId', $customerId)
    //               ->where('pending', 0); // Payment must be cleared
    //     })->get();

    //     return view('patient.pages.completed', compact('completedReports'));
    // }

    // public function viewReport($customerId, $testId)
    // {
    //     // Retrieve customer and test report
    //     $customer = Customer::where('customerId', $customerId)->firstOrFail();
    //     $testReport = TestReport::whereHas('customerTest', function ($query) use ($customerId, $testId) {
    //         $query->where('customerId', $customerId)->where('addTestId', $testId);
    //     })->firstOrFail();

    //     return view('patient.pages.report', compact('customer', 'testReport'));
    // }
    public function viewReport($customerId, $testId)
    {
        // Fetch the test report along with related details
        $report = TestReport::with([
            'customerTest.customer',
            'customerTest.test',
            'reportChildren.relatedTestRange' // Ensure correct relationship
        ])
            ->whereHas('customerTest', function ($query) use ($customerId, $testId) {
                $query->where('customerId', $customerId)
                    ->where('addTestId', $testId);
            })
            ->first();

        // Check if the report exists
        if (!$report) {
            return redirect()->back()->with('error', 'Report not found.');
        }

        // Get the customer's details
        $customer = $report->customerTest->customer;
        $customerGender = $customer->gender ?? null; // Get gender

        // Ensure reportChildren exists before accessing it
        $testRangeIds = $report->reportChildren ? $report->reportChildren->pluck('testRangeId')->toArray() : [];

        // Fetch test ranges filtered by testRangeId and gender
        $testRanges = TestRange::whereIn('testRangeId', $testRangeIds)
            ->where('gender', $customerGender)
            ->get();

        return view('patient.pages.report', compact('report', 'testRanges', 'customer'));
    }
}
