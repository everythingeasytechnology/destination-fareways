<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of admin users.
     */
    public function index()
    {
        $users = User::orderBy('name', 'asc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new admin user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created admin user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'string', 'in:superadmin,admin,editor'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['required', 'boolean'],
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('uploads/avatars', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'avatar' => $avatarPath,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.users.index')->with('success', "Admin user '{$request->name}' created successfully.");
    }

    /**
     * Display the specified admin user details.
     */
    public function show(User $user)
    {
        return redirect()->route('admin.users.edit', $user->id);
    }

    /**
     * Show the form for editing the specified admin user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified admin user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'string', 'in:superadmin,admin,editor'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['required', 'boolean'],
        ]);

        // Self demotion / deactivation prevention check
        if (Auth::id() === $user->id) {
            if (!$request->is_active) {
                return back()->with('error', 'You cannot deactivate your own active session. Please contact another superadministrator.')->withInput();
            }
            if ($user->role === 'superadmin' && $request->role !== 'superadmin') {
                // Check if this is the only superadmin
                $superadminCount = User::where('role', 'superadmin')->where('is_active', true)->count();
                if ($superadminCount <= 1) {
                    return back()->with('error', 'You are the only active superadministrator. Demoting your role is restricted.')->withInput();
                }
            }
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->is_active,
        ];

        // Process Password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Process Avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('uploads/avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', "Admin details for '{$user->name}' updated successfully.");
    }

    /**
     * Remove the specified admin user from storage.
     */
    public function destroy(User $user)
    {
        // Safety guard: prevent self-deletion
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Self-deletion is prohibited for safety reasons.');
        }

        // Prevent deleting the last superadmin
        if ($user->role === 'superadmin') {
            $superadminCount = User::where('role', 'superadmin')->count();
            if ($superadminCount <= 1) {
                return redirect()->route('admin.users.index')->with('error', 'Deleting the sole remaining superadministrator is restricted.');
            }
        }

        // Delete avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' deleted successfully.");
    }
}
