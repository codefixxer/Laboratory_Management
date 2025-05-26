<?php

namespace App\Http\Controllers\Receptionist;

use App\Models\Lc;
use App\Models\Test;
use App\Models\Payment;

// Models
use App\Models\Customer;
use App\Models\Referral;
use App\Models\StaffPanel;
use Termwind\Components\Dd;
use App\Models\CustomerTest;
use App\Models\TestCategory;
use Illuminate\Http\Request;
use App\Models\ExternalPanel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TestSaveController extends Controller
{
    public function getTestsByCategory($testCat)
    {
        $tests = Test::where('testCatId', $testCat)->get();
        return response()->json($tests);
    }
    

 
    /**
     * Display the single-page form with tabs.
     */
    public function showForm()
    {
        // Fetch real categories from your test_categories table
        $categories = \App\Models\TestCategory::all();
    
        // Fetch tests with relationship (if available)
        $availableTests = \App\Models\Test::with('category')->get();
    
        // Fetch staff panel, external panel, and referrer lists (if needed)
        $staffList = \App\Models\StaffPanel::all();
        $externalList = \App\Models\ExternalPanel::all();
        $referrerList = \App\Models\Referral::all();
    
        // Fetch loyalty cards (LC records)
        $loyaltyCards = Lc::select('phone_number', 'percentage')->get();
    
        // Get the next available customer ID
        $nextCustomerId = \App\Models\Customer::max('customerId') + 1;
    
        return view('receptionist.pages.test.testsave', [
            'categories'      => $categories,
            'availableTests'  => $availableTests,
            'staffList'       => $staffList,
            'externalList'    => $externalList,
            'referrerList'    => $referrerList,
            'loyaltyCards'    => $loyaltyCards,
            'nextCustomerId'  => $nextCustomerId, // pass next customer ID to the view
        ]);
    }
    /**
     * Handle the submission of all tabs in one go, saving to:
     * 1) customers
     * 2) customer_tests
     * 3) payments
     * Also update staff's remaining credits if referralType is 'staff'.
     */
 
    public function store(Request $request)
    {
        // Parse JSON tests array if needed
        if ($request->filled('tests') && is_string($request->input('tests'))) {
            $request->merge([
                'tests' => json_decode($request->input('tests'), true)
            ]);
        }

        // Validation
        $validated = $request->validate([
            'relation'      => 'nullable|string|max:255',
            'title'         => 'nullable|string|max:50',
            'user_name'     => 'required|string',
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email',
            'phone'         => 'nullable|string|max:50',
            'gender'        => 'nullable|string',
            'age'           => 'nullable|integer',
            'referralType'  => 'nullable|string|in:normal,staff,external,referrer',
            'staffPanelId'  => 'nullable|integer',
            'id'            => 'nullable|integer',
            'comment'       => 'nullable|string',
            'testDiscount'  => 'nullable|numeric',
            'tests'                 => 'nullable|array',
            'tests.*.addTestId'     => 'required|integer',
            'tests.*.testName'      => 'required|string',
            'tests.*.testCatId'     => 'required|string',
            'tests.*.testCost'      => 'required|numeric',
            'recieved'      => 'nullable|numeric',
            'pending'       => 'nullable|numeric',
        ]);

        DB::beginTransaction();
        try {
            // 1) Create Customer
            $customer = Customer::create([
                'userId'        => Auth::id(),
                'relation'      => $validated['relation'] ?? null,
                'title'         => $validated['title'] ?? null,
                'user_name'     => $validated['user_name'],
                'name'          => $validated['name'],
                'email'         => $validated['email'] ?? null,
                'phone'         => $validated['phone'] ?? null,
                'gender'        => $validated['gender'] ?? null,
                'age'           => $validated['age'] ?? null,
                'extPanelId'    => $validated['referralType'] === 'external'
                                   ? $request->input('externalPanelId') : null,
                'addRefrealId'  => $validated['referralType'] === 'referrer'
                                   ? $request->input('id') : null,
                'staffPanelId'  => $validated['referralType'] === 'staff'
                                   ? $request->input('staffPanelId') : null,
                'comment'       => $validated['comment'] ?? null,
                'testDiscount'  => $validated['testDiscount'] ?? 0,
                'createdDate'   => now(),
            ]);

            // 2) Create CustomerTest rows
            $rawTotal = 0;
            if (!empty($validated['tests'])) {
                foreach ($validated['tests'] as $t) {
                    CustomerTest::create([
                        'addTestId'   => $t['addTestId'],
                        'customerId'  => $customer->customerId,
                        'createdDate' => now(),
                        'testStatus'  => 'pending',
                        'reportDate'  => null,
                    ]);
                    $rawTotal += $t['testCost'];
                }
            }

            // 3) Create Payment
            Payment::create([
                'customerId'  => $customer->customerId,
                'recieved'    => $validated['recieved'] ?? 0,
                'pending'     => $validated['pending']  ?? 0,
                'createdDate' => now(),
            ]);

            // 4) Deduct staff panel credits
            if ($validated['referralType'] === 'staff') {
                $sp = \App\Models\StaffPanel::find($request->input('staffPanelId'));
                if ($sp) {
                    $disc = min($rawTotal, $sp->remainingCredits);
                    $sp->remainingCredits -= $disc;
                    $sp->save();
                }
            }

            // 5) Deduct external panel credits
            if ($validated['referralType'] === 'external') {
                $ep = \App\Models\ExternalPanel::find($request->input('externalPanelId'));
                if ($ep) {
                    $disc = min($rawTotal, $ep->remainingCredits);
                    $ep->remainingCredits -= $disc;
                    $ep->save();
                }
            }

            DB::commit();

            // **Redirect to invoice page**
            return redirect()
                ->route('receptionist.invoice', $customer->customerId);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['msg' => 'Error saving data: '.$e->getMessage()]);
        }
    }

    /**
     * Show the invoice for a just‐created customer.
     */
    public function show($customerId)
    {
        $customer = Customer::with([
            'customerTests.test.category',
            'payment',
            'staffPanel.user',
            'externalPanel',
            'referral'
        ])->findOrFail($customerId);

        return view(
            'receptionist.pages.invoice.index',
            compact('customer')
        );
    }
}


