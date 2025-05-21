<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Seat;

class EnsureMessConveynor
{
    public function handle(Request $request, Closure $next)
    {
        $student = auth('student')->user();
        $seat = Seat::where('id', $student->seat_id)->first();
        $hostel_id = $seat->hostel_id;

        if(!$student){
            abort(403, 'Student not found');
        }

        $role = Role::where('roll_number', $student->roll_number)
                    ->where('hostel_id', $hostel_id)
                    ->first();


        if (!$role || $role->role !== 'Mess Conveynor') {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

