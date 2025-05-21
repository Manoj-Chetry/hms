<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use App\Models\Role;
use App\Models\Room;
use App\Models\Seat;
use App\Models\Student;
use App\Models\Complaint;
use App\Models\Hostel_Change;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $exist = Role::where('roll_number',$request->roll_number)->get();

        if($check){
            $check->delete();
        }
        if($exist){
            $exist->each(function ($role) {
                $role->delete();
            });
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


    public function viewGeneralComplaints(){
        $user = auth('staff')->user();
        $complaints = Complaint::where('hostel_id', $user->hostel_id)
                                ->where('type','general')
                                ->where('status','pending')
                                ->orderBy('created_at', 'desc')
                                ->get();

        $resolved  = Complaint::where('hostel_id', $user->hostel_id)
                                ->where('type','general')
                                ->where('status','resolved')
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('staff.warden.general_complaint', compact('complaints', 'resolved'));
    }
    public function viewRaggingComplaints(){
        $user = auth('staff')->user();
        $complaints = Complaint::where('hostel_id', $user->hostel_id)
                                ->where('type','raggging')
                                ->where('status','pending')
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('staff.warden.general_complaint', compact('complaints'));
    }

    public function resolveComplaint($id){
        $complaint = Complaint::find($id);
        $complaint->status = 'resolved';
        $complaint->resolved_at = date('Y-m-d');
        $complaint->save();

        return redirect()->route('staff.warden.view.general_complaints')->with('success', 'Complaint marked as resolved.');
    }

    public function leaveRequest(){
        $hostelId = auth('staff')->user()->hostel_id;

        $seatIds = Seat::where('hostel_id', $hostelId)->pluck('id');

        $studentRollNumbers = Student::whereIn('seat_id', $seatIds)->pluck('roll_number');

        $leave = Leave::whereIn('student_id', $studentRollNumbers)
                    ->whereIn('status', ['hod:approved', 'pending'])
                    ->get();

        return view('staff.warden.leaverequest', compact('leave'));
    }

    public function leaveApprove($id){
        $leave = Leave::where('id', $id)->first();
        
        $leave->status = 'warden:approved';
        $leave->save();

        return redirect()->back()->with('success', 'Leave request approved successfully.');
    }
    public function leaveReject($id){
        $leave = Leave::where('id', $id)->first();
        
        $leave->status = 'rejected';
        $leave->save();

        return redirect()->back()->with('success', 'Leave request rejected successfully.');
    }

    public function hostelApprove(Request $request){
        $hos = Hostel_Change::where('id', $request->id)->first();
        $hos->status = 'forwarded';
        $hos->save();

        return redirect()->route('staff.warden.hostel.change');
    }
    public function hostelInApprove(Request $request){
        $hos = Hostel_Change::where('id', $request->id)->first();
        $s = Seat::where('id', $request->seat)->first();
        $student = Student::where('roll_number', $hos->student_id)->first();
        $olds = Seat::find($student->seat_id);


        DB::transaction(function() use($hos, $s, $student, $olds){
            $hos->new_seat_id = $s->id;
            $hos->status = 'accepted';
            $hos->save();

            $s->occupied = true;
            $s->save();
            $olds->occupied= false;
            $olds->save();

            $student->seat_id = $s->id;
            $student->seat = $s->seat;
            $student->save();
        });


        return redirect()->route('staff.warden.hostel.change');
    }
    public function hostelReject(Request $request){
        $hos = Hostel_Change::where('id', $request->id)->first();
        $hos->status = 'rejected';
        $hos->save();

        return redirect()->route('staff.warden.hostel.change');
    }
}
