<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Student;
use Illuminate\Http\Request;

class HodController extends Controller
{
    public function leaveRequest(){
        $dept = auth('staff')->user()->department_id;

        // Get student IDs where department matches HOD
        $studentIds = Student::where('department_id', $dept)->pluck('roll_number');

        // Get pending leave requests for those students
        $leave = Leave::where('status', 'pending')
                    ->whereIn('student_id', $studentIds)
                    ->get();

        return view('staff.hod.leaverequest', compact('leave'));
    }

    public function leaveApprove($id){
        $leave = Leave::where('id', $id)->first();
        
        $leave->status = 'hod:approved';
        $leave->save();

        return redirect()->back()->with('success', 'Leave request approved successfully.');
    }
    public function leaveReject($id){
        $leave = Leave::where('id', $id)->first();
        
        $leave->status = 'rejected';
        $leave->save();

        return redirect()->back()->with('success', 'Leave request rejected successfully.');
    }
}
