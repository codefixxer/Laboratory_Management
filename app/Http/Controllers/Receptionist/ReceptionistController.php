<?php

namespace App\Http\Controllers\Receptionist;

use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\TestReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReceptionistController extends Controller
{
  

    public function revoked()
    {
        $customers = Customer::whereHas('tests', function($query) {
            $query->where('testStatus', 'revoked');
        })->with(['tests' => function($query) {
            $query->where('testStatus', 'revoked');
        }])->get();
        
        return view('receptionist.pages.customers.revoked', compact('customers'));
    }










    // public function index()
    // {
    //     $customers = Customer::whereHas('tests', function($query) {
    //         $query->where('testStatus', 'accepted');
    //     })->with(['tests' => function($query) {
    //         $query->where('testStatus', 'accepted');
    //     }])->get();
        
    //     return view('receptionist.pages.customers.signed', compact('customers'));
    // }


    

public function payPending($customerId)
{
    // Retrieve the payment record with pending amount for the customer
    $payment = Payment::where('customerId', $customerId)
                      ->where('pending', '>', 0)
                      ->first();

    if (!$payment) {
        return redirect()->back()->with('error', 'No pending payment found for this customer.');
    }

    $pendingAmount = $payment->pending;

    // Begin transaction
    DB::beginTransaction();

    try {
        // 1. Update the existing record: set pending to 0
        $payment->pending = 0;
        $payment->save();

        // 2. Create a new record with the same customerId
        Payment::create([
            'customerId'  => $payment->customerId,
            'recieved'    => $pendingAmount,
            'pending'     => 0,
        ]);

        DB::commit();

        return redirect()->back()->with('success', 'Pending amount cleared and new payment record created.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to process payment: ' . $e->getMessage());
    }
}

    public function index()
    {
        $reports = TestReport::where('signStatus', 'accepted')
             ->with([
                 'customerTest.test',
                 'customerTest.customer.payments' // Eager load payments for each customer
             ])
             ->get();
        
        return view('receptionist.pages.customers.signed', compact('reports'));
    }



public function show($id)
{







    $report = TestReport::with(['customerTest.test.testRanges', 'reportChildren'])
    ->where('reportId', $id)
    ->first();

if (!$report) {
return redirect()->back()->with('error', 'Report not found.');

}

return view('receptionist.pages.customers.details', compact('report'));

}


    public function viewReport($reportId)
    {
        $report = TestReport::with(['customerTest.test.testRanges', 'reportChildren'])
                    ->where('reportId', $reportId)
                    ->first();
    
        if (!$report) {
            return redirect()->back()->with('error', 'Report not found.');
        }
    
        // dd($report->reportChildren); // Debugging: Check if values are fetched
    
        return view('manager.pages.Pending_report.report-view', compact('report'));
    }


}
