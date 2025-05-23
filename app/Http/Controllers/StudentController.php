<?php

namespace App\Http\Controllers;

use App\Models\Hostel_Change;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\Hostel;
use App\Models\Leave;
use App\Models\MessExpense;
use App\Models\Room;
use App\Models\Seat;
use App\Models\Role;


class StudentController extends Controller
{
    public function viewDashboard(){
        $user = auth('student')->user();
        $seat_id = $user->seat_id;
        $seat = Seat::find($seat_id);
        $room = Room::find($seat->room_id);
        $hostel = Hostel::find($room->hostel_id);
        $hos = Hostel::all();

        $hostels = [];
        foreach($hos as $h){
            if($h->id == $hostel->id || $user->gender != $h->gender){
                continue;
            }
            else{
                $hostels[] = $h;
            }
        }

        $isMessConveynor = Role::where('roll_number', $user->roll_number)
                        ->where('role', 'Mess Conveynor')
                        ->where('hostel_id', $hostel->id)
                        ->exists();

        $messExpenses = MessExpense::where('hostel_id', $hostel->id)->get();

        return view('student.dashboard', compact('hostels', 'hostel', 'isMessConveynor', 'messExpenses','user'));

    }


    public function makeComplaint(Request $request){
        $rollNumber = auth('student')->user()->roll_number;

        $seat= auth('student')->user()->seat;

        $h = explode('-', $seat);

        $hostel = Hostel::where('name', $h[0])->first();

        $data = [
            'student_id' => $rollNumber,
            'hostel_id' => $hostel['id'],
            'issue_description' =>  $request->issue_description,
            'status' => 'pending',
            'created_at' => date('Y-m-d'),
            'resolved_at' => Null,
            'type' => 'general'
        ];
        if(Complaint::create($data)){
            return redirect()->route('student.dashboard')
            ->with('success', 'Complaint Registered Successfully!');
        }
        else{
            return redirect()->route('student.dashboard')
            ->withErrors('errror', 'Failed to Register Complaint!');
        }
    }


    public function makeRaggingComplaint(Request $request){
        $rollNumber = auth('student')->user()->roll_number;

        $seat= auth('student')->user()->seat;

        $h = explode('-', $seat);

        $hostel = Hostel::where('name', $h[0])->first();

        $data = [
            'student_id' => $rollNumber,
            'hostel_id' => $hostel['id'],
            'issue_description' =>  $request->issue_description,
            'status' => 'pending',
            'created_at' => date('Y-m-d'),
            'resolved_at' => Null,
            'type' => 'ragging'
        ];
        if(Complaint::create($data)){
            return redirect()->route('student.dashboard')
            ->with('success', 'Complaint Registered Successfully!');
        }
        else{
            return redirect()->route('')
            ->withErrors('errror', 'Failed to Register Complaint!');
        }
    }


    public function applyLeave(Request $request){
        // dd($request);
        $request->validate([
            'reason' => 'required|string',
            'deperature_time' => 'required',
            'arrival_time' => 'required'
        ]);

        $rollNumber = auth('student')->user()->roll_number;
        $data = [
            'student_id' => $rollNumber,
            'reason' => $request->reason,
            'deperature_time' => $request->deperature_time,
            'arrival_time' => $request->arrival_time,
            'status' => 'pending',
            'done' => false
        ];

        if(Leave::create($data)){
            return redirect()->route('student.dashboard')
            ->with('success', 'Leave Applied Successfully!');
        }
        else{
            return redirect()->route('')
            ->withErrors('errror', 'Failed to Apply Leave!');
        }
    }

    public function hostelChange(Request $request){
        $request->validate([
            'destination_hostel' => 'required'
        ]);

        $rollNumber = auth('student')->user()->roll_number;
        $data = [
            'student_id' => $rollNumber,
            'destination_hostel_id' => $request->destination_hostel,
            'new_seat_id' => Null,
            'created' => date('Y-m-d'),
            'status' => 'pending'
        ];
        
        if(Hostel_Change::create($data)){
            return redirect()->route('student.dashboard')
            ->with('success', 'Hostel Change Request Applied Successfully!');
        }
        else{
            return redirect()->route('')
            ->withErrors('errror', 'Failed to Apply Hostel Change!');
        }
    }

    public function myActions(){
        $rollNumber = auth('student')->user()->roll_number;


        $complaints = Complaint::where('student_id', $rollNumber)->get();
        $leaves = Leave::where('student_id',$rollNumber)->get();

        return view('student.myAction', compact('complaints','leaves'));
    }
}
