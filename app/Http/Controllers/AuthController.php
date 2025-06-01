<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Display the login form.
     *
     * @return \Illuminate\View\View
     */
    public function roleerror()
    {
        return view('auth.errors.error403'); // Ensure this view exists (resources/views/auth/login.blade.php)
    }
    public function showLoginForm()
    {
        return view('auth.staff.login'); // Ensure this view exists (resources/views/auth/login.blade.php)
    }
    
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

     public function patient()
     {
         return view('auth.patient.login'); // Ensure this view exists (resources/views/auth/login.blade.php)
     }
     

   public function loginp(Request $request)
{
    $request->validate([
        'login_id' => 'required',
        'password' => 'required',
    ]);

    // Try to find by user_name OR phone
    $customer = \App\Models\Customer::where('user_name', $request->login_id)
        ->orWhere('phone', $request->login_id)
        ->first();

    if (!$customer) {
        return back()->withInput()->withErrors([
            'login_id' => 'No user found with this User ID or Phone.',
        ]);
    }

    // Check password (plain text)
    if ($customer->password !== $request->password) {
        return back()->withInput()->withErrors([
            'password' => 'Incorrect password.',
        ]);
    }

    // Set session and login
    session([
        'customerId' => $customer->customerId,
        'customerName' => $customer->name,
    ]);

    return redirect()->route('patient.dashboard');
}




     public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    $remember = $request->has('remember');

    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->status !== 'active') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact the laboratory manager or admin.',
            ])->onlyInput('email');
        }

        return match ($user->role) {
            'admin'         => redirect()->route('admin.dashboard'),
            'receptionist'  => redirect()->route('receptionist.dashboard'),
            'sampler'        => redirect()->route('sampler.dashboard'),
            'reporter'          => redirect()->route('reporter.dashboard'),
            'manager'          => redirect()->route('manager.dashboard'),
            'patient'          => redirect()->route('patient.dashboard'),
            default         => redirect()->intended('/'),
        };
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}

    
    /**
     * Log the user out and invalidate the session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
}

    
}
