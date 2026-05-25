<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Please log in to access the Admin Panel.');
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Your account has been deactivated. Please contact support.');
        }

        // Check if user has an admin role
        if (!in_array($user->role, ['superadmin', 'admin', 'editor'])) {
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Access denied. You do not have permissions to access the Admin Panel.');
        }

        return $next($request);
    }
}
