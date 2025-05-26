<?php
namespace App\Http\Controllers\Patient;

use App\Models\Customer;
use App\Models\TestRange;
use App\Models\TestReport;
use App\Models\CustomerTest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function pendingReports()
    {
        // Get logged-in patient/customer ID
        $customerId = session('customerId');
    
        // Debugging: Check if customerId is being retrieved correctly
        if (!$customerId) {
            dd('Customer ID not found in session');
        }
    
        // Retrieve test reports where:
        // - signStatus is "pending"
        // - OR testStatus is "pending" in customer_tests
        // - OR pending_amount > 0 in payments
        $pendingReports = TestReport::whereHas('customerTest', function ($query) use ($customerId) {
            $query->where('customerId', $customerId)
                  ->where(function ($q) {
                      $q->where('testStatus', 'pending') // Check pending testStatus
                        ->orWhereHas('customer.payment', function ($p) {
                            $p->where('pending', '>', 0); // Check pending payment
                        });
                  });
        })->orWhere('signStatus', 'pending') // Check pending signStatus
        ->get();
    
        return view('patient.pages.pending', compact('pendingReports'));
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
    public function completedReports()
{
    $customerId = session('customerId');

    $completedReports = TestReport::whereHas('customerTest', function ($query) use ($customerId) {
        $query->where('customerId', $customerId)
              ->where('testStatus', 'accepted');
    })->whereHas('customerTest.customer.payment', function ($query) use ($customerId) {
        $query->where('customerId', $customerId)->where('pending', 0);
    })->with(['customerTest.customer']) // Include customer details
      ->get();

    return view('patient.pages.completed', compact('completedReports'));
}
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
