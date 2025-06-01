<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.pages.users.index', compact('users'));
    }

    public function create()
    {
        // These roles can exist only once in the system
        $singleRoles = ['manager', 'sampler', 'reporter'];
        $usedRoles = User::whereIn('role', $singleRoles)->pluck('role')->toArray();

        return view('admin.pages.users.create', compact('usedRoles'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        // For edit, allow the current user's role to be selectable
        $singleRoles = ['manager', 'sampler', 'reporter'];
        $usedRoles = User::whereIn('role', $singleRoles)
            ->where('id', '!=', $id)
            ->pluck('role')->toArray();

        return view('admin.pages.users.create', compact('user', 'usedRoles'));
    }

    public function store(Request $request)
    {
        $singleRoles = ['manager', 'sampler', 'reporter'];
        $role = $request->input('role');

        // Receptionist is unlimited; others only if not already present
        if (in_array($role, $singleRoles)) {
            $already = User::where('role', $role)->exists();
            if ($already) {
                return back()->withInput()->withErrors([
                    'role' => ucfirst($role) . ' already exists. Only one ' . $role . ' allowed.'
                ]);
            }
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
            'role' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'profile_picture' => 'nullable|image',
            'status' => 'required|in:active,inactive'
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $data['profile_picture'] = $path;
        }

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $singleRoles = ['manager', 'sampler', 'reporter'];
        $role = $request->input('role');

        // If changing role, block assigning single role to another user
        if (
            in_array($role, $singleRoles)
            && $user->role !== $role // Only if trying to change the role
        ) {
            $already = User::where('role', $role)->where('id', '!=', $id)->exists();
            if ($already) {
                return back()->withInput()->withErrors([
                    'role' => ucfirst($role) . ' already exists. Only one ' . $role . ' allowed.'
                ]);
            }
        }

        $rules = [
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $id,
            'role'   => 'required|string',
            'phone'  => 'nullable|string',
            'address'=> 'nullable|string',
            'status' => 'required|in:active,inactive',
            'profile_picture' => 'nullable|image',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $data = $request->validate($rules);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $data['profile_picture'] = $path;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User status updated successfully.');
    }
}
