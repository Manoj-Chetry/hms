<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Hostel;
use App\Models\Room;
use App\Models\Seat;
use App\Models\Student;



class CaretakerController extends Controller
{
    public function studentDetails(){
        $hostel_id = auth('staff')->user()->hostel_id;
        $roomIds = Room::where('hostel_id', $hostel_id)->pluck('id');
        $seatIds = Seat::whereIn('room_id', $roomIds)->pluck('id');
        $students = Student::whereIn('seat_id', $seatIds)->get();

        return view('staff.caretaker.student_details', compact('students'));
    }


    public function roomDetails(){
        $hostel_id = auth('staff')->user()->hostel_id;
        $hostel = Hostel::where('id',$hostel_id)->first();

        $seats = Seat::where('hostel_id',$hostel_id)->get();
        $empty_seats = Seat::where('occupied', false)->get();


        return view('staff.caretaker.room_details', compact('hostel', 'seats','empty_seats'));
        
    }


    public function addSingleRoom(Request $request){
    $hostel_id = auth('staff')->user()->hostel_id;
    $hostel_name = Hostel::where('id', $hostel_id)->first()->name;

    $check = Room::where('room_number', $request->room_number)
        ->where('hostel_id', $hostel_id)
        ->exists();

    if ($check) {
        return redirect()->back()->withErrors('Room already exists in this hostel.');
    }

    $data = [
        'capacity' => $request->capacity,
        'room_number' => $request->room_number,
        'hostel_id' => $hostel_id,
    ];

    if (Room::create($data)) {
        Hostel::where('id', $hostel_id)->increment('number_of_rooms');
        $room = Room::where('hostel_id', $hostel_id)
            ->where('room_number', $request->room_number)
            ->first();


        $roomPrefix = $hostel_name . '-' . $request->room_number . '-';
        for ($i = 1; $i <= $request->capacity; $i++) {
            $seatIdentifier = $roomPrefix . chr(64 + $i);

            $success = Seat::create([
                'seat' => $seatIdentifier,
                'room_id' => $room->id,
                'hostel_id' => $hostel_id,
                'occupied' => false,
            ]);
            if($success){
                Hostel::where('id', $hostel_id)->increment('number_of_seats');
            }
        }

        return redirect()->route('staff.caretaker.room_details')
            ->with('success', 'Room and seats created successfully!');
    }
}



    public function addMultipleRoom(Request $request)
    {
        $hostel_id = auth('staff')->user()->hostel_id;
        $hostel_name = Hostel::where('id', $hostel_id)->first()->name;
    
        $s = $request->room_start;
        $e = $request->room_end;
    
        $exist = [];
        $created = [];
    
        while ($s <= $e) {
            $check = Room::where('room_number', $s)
                ->where('hostel_id', $hostel_id)
                ->exists();
    
            if ($check) {
                $exist[] = $s;
                $s++;
                continue;
            }
    
            $data = [
                'capacity' => $request->capacity,
                'room_number' => $s,
                'hostel_id' => $hostel_id,
            ];
    
            if (Room::create($data)) {
                Hostel::where('id', $hostel_id)->increment('number_of_rooms');
                $room = Room::where('room_number', $s)->first();
    
                $roomPrefix = $hostel_name . '-' . $s . '-';
                for ($i = 1; $i <= $request->capacity; $i++) {
                    $seatIdentifier = $roomPrefix . chr(64 + $i);
    
                    $success = Seat::create([
                        'seat' => $seatIdentifier,
                        'room_id' => $room->id,
                        'hostel_id' => $hostel_id,
                        'occupied' => false,
                    ]);
                    if($success){
                        Hostel::where('id', $hostel_id)->increment('number_of_seats');
                    }
                }
    
                $created[] = $s; 
            } else {
                $exist[] = $s;
            }
    
            $s++;
        }
    
        if (empty($exist)) {
            return redirect()->back()->with('success', 'Rooms and seats created successfully!');
        } elseif (empty($created)) {
            return redirect()->back()->withErrors('Failed to create rooms and seats.');
        } else {
            return redirect()->back()->with('success', 'Rooms Created: ' . implode(', ', $created) . '<br>Failed to create: ' . implode(', ', $exist));
        }
    }


    public function viewGeneralComplaints(){
        $user = auth('staff')->user();
        $complaints = Complaint::where('hostel_id', $user->hostel_id)
                                ->where('type','general')
                                ->where('status','pending')
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('staff.caretaker.general_complaint', compact('complaints'));
    }
    public function viewRaggingComplaints(){
        $user = auth('staff')->user();
        $complaints = Complaint::where('hostel_id', $user->hostel_id)
                                ->where('type','raggging')
                                ->where('status','pending')
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('staff.caretaker.general_complaint', compact('complaints'));
    }

    public function resolveComplaint($id){
        $complaint = Complaint::find($id);
        $complaint->status = 'resolved';
        $complaint->resolved_at = date('Y-m-d');
        $complaint->save();

        return redirect()->route('staff.caretaker.view.general_complaints')->with('success', 'Complaint marked as resolved.');
    }
    
}


