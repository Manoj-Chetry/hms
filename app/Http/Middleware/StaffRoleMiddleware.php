<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class StaffRoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        $user = Auth::guard('staff')->user();

        if (!$user || $user->role !== $role) {
            // Redirect to the appropriate dashboard based on the user's role
            switch ($user->role) {
                case 'warden':
                    return redirect()->route('staff.warden.dashboard');
                case 'caretaker':
                    return redirect()->route('staff.caretaker.dashboard');
                case 'attender':
                    return redirect()->route('staff.attender.dashboard');
                case 'hod':
                    return redirect()->route('staff.hod.dashboard');
                default:
                    return redirect()->route('staff.dsw.dashboard'); // Default to DSW if no match
            }
        }

        return $next($request);
    }
}
