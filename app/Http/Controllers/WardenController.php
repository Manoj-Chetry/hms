<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use App\Models\Room;
use App\Models\Seat;
use App\Models\Student;
use Illuminate\Http\Request;

class WardenController extends Controller
{
    public function viewDashboard(){
        $user = auth('staff')->user();
        $hostel = Hostel::where('id', $user->hostel_id)->first();
        $rooms = Room::where('hostel_id', $hostel->id)->get();
        $seats = Seat::where('hostel_id', $hostel->id)->get();

        return view('staff.warden_dashboard', compact('user', 'hostel', 'rooms', 'seats'));
    }


    public function studentDetails(){
        $user = auth('staff')->user();
        $roomIds = Room::where('hostel_id', $user->hostel_id)->pluck('id');
        $seatIds = Seat::whereIn('room_id', $roomIds)->pluck('id');
        $students = Student::whereIn('seat_id', $seatIds)->get();

        return view('staff.warden.students_details', compact('students', 'user'));
    }

    public function studentSearch(Request $request)
    {
        $user = auth('staff')->user();
        $hostel_id = $user->hostel_id;

        // return $hostel_id;
        $query = Student::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('roll_number', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
        }

        $students = $query->get(); 

        return view('staff.warden.students_details', compact('students','user'));
    }
}
