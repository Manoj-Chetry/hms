<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use App\Models\Role;
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

        $roles = Role::where('hostel_id', $user->hostel_id)->get();

        return view('staff.warden_dashboard', compact('user', 'hostel', 'rooms', 'seats','roles'));
    }


    public function studentDetails(){
        $user = auth('staff')->user();
        $roomIds = Room::where('hostel_id', $user->hostel_id)->pluck('id');
        $seatIds = Seat::whereIn('room_id', $roomIds)->pluck('id');
        $students = Student::whereIn('seat_id', $seatIds)->get();

        return view('staff.warden.students_details', compact('students', 'user'));
    }

    public function studentSearch(Request $request){
        $user = auth('staff')->user();
        $hostel_id = $user->hostel_id;

        $query = Student::query();

        // Filter students by search term
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('roll_number', 'like', "%{$search}%")
                ->orWhere('department', 'like', "%{$search}%");
            });
        }

        // Filter students by hostel ID
        $query->whereHas('seat.room.hostel', function ($q) use ($hostel_id) {
            $q->where('id', $hostel_id);
        });

        $students = $query->get();

        return view('staff.warden.students_details', compact('students', 'user'));
    }


    public function assignRole(Request $request){
        $hostel_id = auth('staff')->user()->hostel_id;
        $name = Student::where('roll_number',$request->roll_number)->first();

        $check = Role::where('hostel_id', $hostel_id)->where('role', $request->role)->first();

        if($check){
            $check->delete();
        }

        $data = [
            'hostel_id' => $hostel_id,
            'roll_number' => $request->roll_number,
            'name' => $name->name,
            'role' => $request->role
        ];
            
        if(Role::create($data)){
            return redirect()->route('staff.warden.student_details')
            ->with('success', 'Role Added successfully!');
        }else{
            return redirect()->route('staff.warden.student_details')
            ->withErrors(['error'=> 'Failed Adding Role!']);
        }

    }
}
