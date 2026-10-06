<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditTrail;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Display User Management Page
    public function indexUsers()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    // Display Audit Trail Log Page
    public function auditTrail()
    {
        $logs = AuditTrail::with(['user', 'material'])->latest()->get();
        return view('admin.audit-trail', compact('logs'));
    }

    // Register New User
    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|string',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => strtolower($request->role), // Store as lowercase to prevent 403 errors
        ]);

        return back()->with('success', 'User registered successfully!');
    }

    // Update User Details (Name & Email)
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'User information updated successfully!');
    }

    // Update User Role
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string',
        ]);

        $user->update([
            'role' => strtolower($request->role), // Store as lowercase to prevent 403 errors
        ]);

        return back()->with('success', 'User role updated successfully!');
    }

    // Delete User
    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully!');
    }
}