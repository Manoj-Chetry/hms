<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use App\Models\Leave;
use App\Models\OutRecord;
use App\Models\Seat;
use App\Models\Student;
use Illuminate\Http\Request;

class AttenderController extends Controller
{
    public function outRecord(){
        $hostelId = auth('staff')->user()->hostel_id;
        $hos = Hostel::where('id', $hostelId)->first();

        $seatIds = Seat::where('hostel_id', $hostelId)->pluck('id');

        $studentRollNumbers = Student::whereIn('seat_id', $seatIds)->pluck('roll_number');

        $leave = Leave::whereIn('student_id', $studentRollNumbers)
                    ->where('status',  'warden:approved')
                    ->where('done', 'false')
                    ->get();

        
        $out = OutRecord::whereIn('student_id', $studentRollNumbers)
                        ->get();

        $rec = OutRecord::whereIn('student_id', $studentRollNumbers)
                        ->whereNotNull('in_date')
                        ->get();

        return view('staff.attender_dashboard', compact('leave','out', 'rec','hos'));
    }


    public function outEntry($id){
        $leave = Leave::find($id);
        $leave->done = true;
        $leave->save();

        $data = [
            'student_id' => $leave->student_id,
            'out_date' => date('Y-m-d'),
            'in_date' => Null
        ];
        
        if(OutRecord::create($data)){
            return redirect()->back()->with('success', 'Out Recorded Successfully');
        }
    }
    public function inEntry($id){
        $in = OutRecord::find($id);
        $in->in_date = date('Y-m-d');
        if($in->save()){
            return redirect()->back()->with('success', 'In Recorded Successfully');
        }
    }

    public function display(){
        $hostelId = auth('staff')->user()->hostel_id;

        $seatIds = Seat::where('hostel_id', $hostelId)->pluck('id');

        $studentRollNumbers = Student::whereIn('seat_id', $seatIds)->pluck('roll_number');

        $out = OutRecord::whereIn('student_id', $studentRollNumbers)
                        ->whereNotNull('in_date')
                        ->get();
        
    }
}
