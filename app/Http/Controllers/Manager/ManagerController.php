<?php

namespace App\Http\Controllers\Manager;

use App\Models\TestReport;
use App\Models\CustomerTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ManagerController extends Controller
{
    // Display all reports with "Pending" status
    public function pendingReports()
    {
        $reports = TestReport::with(['customerTest.customer', 'customerTest.test'])
                    ->where('signStatus', 'Pending')
                    ->get();

        return view('manager.pages.Pending_report.pending-reports', compact('reports'));
    }

    // // View full report details
    // public function viewReport($reportId)
    // {
    //     $report = TestReport::with(['customerTest.customer', 'customerTest.test.testRanges', 'testReportChild'])
    //                 ->where('reportId', $reportId)
    //                 ->first();

    //     if (!$report) {
    //         return redirect()->route('manager.pendingReports')->with('error', 'Report not found.');
    //     }

    //     return view('manager.pages.report-view', compact('report'));
    // }
//     public function viewReport($reportId)
// {
//     $report = TestReport::with(['customerTest.test.testRanges', 'reportChildren'])
//                 ->where('reportId', $reportId)
//                 ->first();

//     if (!$report) {
//         return redirect()->back()->with('error', 'Report not found.');
//     }

//     // dd($report->reportChildren); // Debugging: Check if values are fetched

//     return view('manager.pages.Pending_report.report-view', compact('report'));
// }
public function viewReport($reportId)
{
    // Fetch the report along with customer and test details
    $report = TestReport::with(['customerTest.customer', 'customerTest.test', 'reportChildren'])
                        ->where('reportId', $reportId)
                        ->first();

    // Check if report exists
    if (!$report) {
        return redirect()->back()->with('error', 'Report not found.');
    }

    // Get the customer's details
    $customer = $report->customerTest->customer;
    $customerId = $customer->customerId ?? null; // Ensure customerId is defined
    $customerGender = $customer->gender ?? null; // Get gender

    // Get only the test ranges that match the customer's gender
    $testRanges = $report->customerTest->test->testRanges()
                        ->where('gender', $customerGender)
                        ->get();

    return view('manager.pages.Pending_report.report-view', compact('report', 'customerId', 'testRanges', 'customerGender'));
}
public function updateSignStatus(Request $request, $reportId)
{
    $report = TestReport::find($reportId);
    if (!$report) {
        return redirect()->back()->with('error', 'Report not found.');
    }
    $newStatus = $request->action;
    DB::beginTransaction();

    try {
        // Update signStatus in testreport table
        $report->signStatus = $newStatus;
        $report->save();

        // Update testStatus in customer_tests table
        CustomerTest::where('ctId', $report->ctId)
            ->update(['testStatus' => $newStatus]);

        DB::commit();

        return redirect()->back()->with('success', 'Sign status and test status updated successfully.');

    } catch (\Exception $e) {
        DB::rollback();

        return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
    }
}


public function showRevokedReports()
{
    $reports = TestReport::where('signStatus', 'revoked')->with('customerTest.test')->get();
    
    return view('manager.pages.Revoke_report.revoked-reports', compact('reports'));
}

public function showAcceptedReports()
{
    $reports = TestReport::where('signStatus', 'accepted')->with('customerTest.test')->get();
    
    return view('manager.pages.Accepted_reports.accepted-reports', compact('reports'));
}

}

