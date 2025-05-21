<?php

namespace App\Http\Controllers;

use App\Models\Hostel_Change;
use App\Models\Room;
use App\Models\Seat;
use Illuminate\Http\Request;
use App\Models\Hostel;
// use Illuminate\Support\Facades\Log;

class DswController extends Controller
{
    public function viewDashboard(){
        $hostelChange = Hostel_Change::whereNot('status', 'rejected')->get();
        $hostels = Hostel::all();
        return view('staff.dsw_dashboard', compact('hostels', 'hostelChange'));
    }

    public function hostelForward(Request $request){
        $hosCh = Hostel_Change::where('id', $request->id)->first();
        $hosCh->status = 'forwarded';
        $hosCh->save();

        return redirect()->route('staff.dsw.dashboard');
    }
    public function hostelApprove(){}
    public function hostelReject(Request $request){
        $hosCh = Hostel_Change::where('id', $request->id)->first();
        $hosCh->status = 'rejected';
        $hosCh->save();

        return redirect()->route('staff.dsw.dashboard');
    }

    public function addHostel(Request $request){
        // return $request;
        $request->validate([
            'name' => 'required',
            'number_of_rooms' => 'integer|min:0',
            'number_of_seats' => 'integer|min:0'
        ]);

        
        $hostel = Hostel::where('name',$request->name)->first(); 

        if($hostel){
            return redirect()->route('staff.dsw.dashboard')
            ->withErrors(['name' => 'Hostel already exists']);

        }
        $data = [
            'name' => $request->name,
        ];
        
        $data['number_of_rooms'] = 0;
        $data['gender'] = $request->gender;
        $data['number_of_seats'] = 0;
        $data['floors'] = $request->floors;
        
        if(Hostel::create($data)){
            return redirect()->route('staff.dsw.dashboard')
           ->with('success', 'Hostel added successfully!');
        }
        
    }


    public function viewHostel($name){
        $hostel = Hostel::where('name',$name)->get()->first();
        $rooms = Room::where('hostel_id', $hostel->id)->get();
        $seats = Seat::where('hostel_id', $hostel->id)->get();

        $vacant = Seat::where('hostel_id', $hostel->id)->where('occupied',false)->get();

        return view('staff.dsw.hostel', compact('hostel','rooms','seats', 'vacant'));
    }
}