<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Customer;
use App\Models\CustomerTest;
use App\Models\TestReport;
use App\Models\TestRange;

class PatientController extends Controller
{
    /**
     * Show dashboard counts and simple graph for all accounts
     * that share the same phone number as the logged-in user.
     */
    public function dashboard()
    {
        // 1) Get logged-in customerId from session, redirect if missing
        $customerId = Session::get('customerId');
        if (! $customerId) {
            return redirect()
                ->route('patient.login')
                ->withErrors(['login' => 'Session expired. Please log in again.']);
        }

        // 2) Find that Customer. If not found, force re-login.
        $customer = Customer::find($customerId);
        if (! $customer) {
            return redirect()
                ->route('patient.login')
                ->withErrors(['login' => 'Account not found. Please log in again.']);
        }

        // 3) Fetch all customer IDs that share this same phone number
        $phone = $customer->phone;
        $samePhoneIds = Customer::where('phone', $phone)
            ->pluck('customerId')
            ->toArray();

        // 4) Count “accepted” vs “pending” for any of those IDs
        $acceptedReports = CustomerTest::whereIn('customerId', $samePhoneIds)
            ->where('testStatus', 'accepted')
            ->count();

        $pendingReports = CustomerTest::whereIn('customerId', $samePhoneIds)
            ->where('testStatus', '!=', 'accepted')
            ->count();

        $totalReports = $acceptedReports + $pendingReports;

        // 5) Prepare minimal graph data
        $graphData = [
            'Pending'  => $pendingReports,
            'Accepted' => $acceptedReports,
        ];

        // 6) We’ll pass the “primary” customer (the one who logged in)
        //    plus the “group” phone so the view can show their name, etc.
        return view('patient.pages.dashboard', compact(
            'totalReports',
            'pendingReports',
            'acceptedReports',
            'graphData',
            'customer',
            'samePhoneIds'
        ));
    }

    /**
     * Show all pending tests (status != 'accepted') for every account
     * that shares the same phone number.
     */
    public function pendingReports()
    {
        $customerId = Session::get('customerId');
        if (! $customerId) {
            return redirect()
                ->route('patient.login')
                ->withErrors(['login' => 'Session expired. Please log in again.']);
        }

        $customer = Customer::find($customerId);
        if (! $customer) {
            return redirect()
                ->route('patient.login')
                ->withErrors(['login' => 'Account not found. Please log in again.']);
        }

        $phone = $customer->phone;
        $samePhoneIds = Customer::where('phone', $phone)
            ->pluck('customerId')
            ->toArray();

        $pendingTests = CustomerTest::whereIn('customerId', $samePhoneIds)
            ->where('testStatus', '!=', 'accepted')
            ->with('test', 'customer')
            ->orderByDesc('created_at')
            ->get();

        return view('patient.pages.pending', compact('pendingTests', 'customer'));
    }

    /**
     * Show all completed tests (status == 'accepted') for every account
     * that shares the same phone number.
     */
    public function completedReports()
    {
        $customerId = Session::get('customerId');
        if (! $customerId) {
            return redirect()
                ->route('patient.login')
                ->withErrors(['login' => 'Session expired. Please log in again.']);
        }

        $customer = Customer::find($customerId);
        if (! $customer) {
            return redirect()
                ->route('patient.login')
                ->withErrors(['login' => 'Account not found. Please log in again.']);
        }

        $phone = $customer->phone;
        $samePhoneIds = Customer::where('phone', $phone)
            ->pluck('customerId')
            ->toArray();

        $completedTests = CustomerTest::whereIn('customerId', $samePhoneIds)
            ->where('testStatus', 'accepted')
            ->with(['test', 'customer.payments'])
            ->orderByDesc('created_at')
            ->get();

        // Sum up “pending” from payments across all those Customer records
        $totalPending = Customer::whereIn('customerId', $samePhoneIds)
            ->with('payments')
            ->get()
            ->pluck('payments')
            ->flatten()
            ->sum('pending');

        return view('patient.pages.completed', compact('completedTests', 'totalPending', 'customer'));
    }

    /**
     * Download PDF only if that report belongs to one of our “same phone” accounts.
     */
    public function downloadReport($reportId)
    {
        $customerId = Session::get('customerId');
        if (! $customerId) {
            return redirect()
                ->route('patient.login')
                ->withErrors(['login' => 'Session expired. Please log in again.']);
        }

        $customer = Customer::find($customerId);
        if (! $customer) {
            return redirect()
                ->route('patient.login')
                ->withErrors(['login' => 'Account not found. Please log in again.']);
        }

        // Build our “same phone” list
        $samePhoneIds = Customer::where('phone', $customer->phone)
            ->pluck('customerId')
            ->toArray();

        // Eager-load then findOrFail
        $report = TestReport::with([
            'customerTest.customer',
            'customerTest.test',
            'reportChildren.relatedTestRange',
        ])->findOrFail($reportId);

        // Only allow if that report’s customerId is in our same-phone group
        $ownerId = $report->customerTest->customer->customerId;
        if (! in_array($ownerId, $samePhoneIds, true)) {
            abort(403, 'Unauthorized');
        }

        $primaryCustomer = $report->customerTest->customer;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'patient.pages.report-pdf',
            ['report' => $report, 'customer' => $primaryCustomer]
        )->setPaper('a4', 'portrait');

        return $pdf->download("medical-report-{$report->reportId}.pdf");
    }

    /**
     * Show a single HTML report if it belongs to any “same phone” account.
     */
    public function viewReport($testId)
    {
        $customerId = Session::get('customerId');
        if (! $customerId) {
            return redirect()
                ->route('patient.login')
                ->withErrors(['login' => 'Session expired. Please log in again.']);
        }

        $customer = Customer::find($customerId);
        if (! $customer) {
            return redirect()
                ->route('patient.login')
                ->withErrors(['login' => 'Account not found. Please log in again.']);
        }

        $samePhoneIds = Customer::where('phone', $customer->phone)
            ->pluck('customerId')
            ->toArray();

        // Find the specific CustomerTest by addTestId, limiting to our same-phone group
        $customerTest = CustomerTest::whereIn('customerId', $samePhoneIds)
            ->where('addTestId', $testId)
            ->with(['test', 'customer', 'reportChildren'])
            ->first();

        if (! $customerTest) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Report not found.']);
        }

        // Now fetch any test ranges that match the customer’s gender
        $customerGender = $customerTest->customer->gender;
        $testRangeIds = $customerTest->reportChildren->pluck('testRangeId')->toArray();

        $testRanges = TestRange::whereIn('testRangeId', $testRangeIds)
            ->where('gender', $customerGender)
            ->get();

        return view('patient.pages.report', [
            'customerTest' => $customerTest,
            'testRanges'   => $testRanges,
            'customer'     => $customer,
        ]);
    }
}
