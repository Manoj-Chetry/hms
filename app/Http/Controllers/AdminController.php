<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Hostel;
use App\Models\Seat;
use App\Models\Department;

use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function fetchSeat($seat)
    {
        $s = Seat::where('seat',$seat)->first();
        if($s){
            return $s;
        }
        else{
            return false;
        }
    }

    public function fetchDept($dept){
        $d = Department::where('name',$dept)->first();
        if($d){
            return $d;
        }else{
            return false;
        }
    }


    public function uploadCSV(Request $request)
    {
        $request->validate([
            'student_csv' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('student_csv');
        $handle = fopen($file, 'r');

        // Skip the header
        fgetcsv($handle);

        $skip = [];
        $seat = [];

        while (($data = fgetcsv($handle)) !== FALSE) {
            $data = array_map('trim', $data);

            if (Student::where('roll_number', $data[1])->exists()) {
                $skip[] = $data[1];
                continue;
            }

            $s = $this->fetchSeat($data[5]);

            if($s==false){
                $skip[] = $data[1];
                continue;
            }

            if (Student::where('seat', $s['seat'])->exists()) {// change
                $seat[] = $data[1];
                continue;
            }

            $d = $this->fetchDept($data[2]);
            if($d==false){
                $skip = $data[1];
            }

            $inserted = Student::create([
                'name' => $data[0],
                'roll_number' => $data[1],
                'department' => $data[2],
                'department_id' => $d['id'],
                'email' => $data[3],
                'phone' => $data[4],
                'seat' => $data[5],
                'seat_id' => $s['id'], 
                'password' => "123456"
            ]);
            if($inserted){
                Seat::where('id', $s['id'])->update(['occupied' => true]);
            }else{
                $seat[] = $data[1];
            }
        }

        fclose($handle);

        if(empty($skip)&&empty($seat)){
            return redirect()->back()->with('success', 'Students imported successfully!');
        }
        else{
            return redirect()->back()->with('success', 'Students imported successfully! Skipped: ' . implode(', ', $skip).' Duplicate seat for:'. implode(', ', $seat));
        }

    }

    public function staffFetchAll() {
        $staffs = Staff::all();
        $hostels = Hostel::all();
        $departments = Department::all();
        return view('admin.staffs.table', compact('staffs', 'hostels', 'departments'));
    }
    

   // AdminController.php

   public function deleteStaff($id)
   {
       // Find the staff by ID
       $staff = Staff::findOrFail($id);
   
       // Delete the staff record
       $staff->delete();
   
       // Redirect to the dashboard and pass session data to open the staff section
       return redirect()->route('admin.dashboard')
           ->with('success', 'Staff deleted successfully!')
           ->with('showStaff', true);
   }


   public function editStaff($id) {
    $staff = Staff::findOrFail($id);

    return redirect()->route('admin.dashboard')
    ->with('success', 'Staff edited successfully!')
    ->with('showStaff', true);

   }


   public function addStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'role' => 'required|in:dsw,caretaker,attender,hod,warden',
            'department_id' => 'nullable|min:0',
            'hostel_id' => 'nullable|min:0'
        ]);

        $staff = Staff::where('email', $request->email)->first();
        if ($staff) {
            return redirect()->route("admin.dashboard")
                ->withErrors(['error'=> 'Staff with this email already exists!'])
                ->with('showStaff', true);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'department_id' => $request->filled('department_id') ? $request->department_id : null,
            'hostel_id' => $request->filled('hostel_id') ? $request->hostel_id : null,
            'password' => '123456'
        ];


        try {
            Staff::create($data);
            return redirect()->route('admin.dashboard')
                ->with('success', 'Staff created successfully!')
                ->with('showStaff', true);
        } catch (\Exception $e) {
            Log::error("Staff creation failed: " . $e->getMessage());
            return redirect()->route("admin.dashboard")
                ->withErrors(['error'=> 'Staff creation failed'])
                ->with('showStaff', true);
        }
    }

   

   public function addDepartment(Request $request){
       $request->validate([
          'name' => 'required|min:2'
       ]);
       
       $data = [
        'name' => $request->name
       ];

       if(Department::create($data)){
            return redirect()->route('admin.departments.all')->with('success', 'Department Added successfully!');
       }
       else{
            return redirect()->route('admin.departments.all')->withErrors(['error'=>'Failed to add Department']);
       }
   }

   public function deleteDepartment($id)
   {
       $dept = Department::findOrFail($id);
   
       $dept->delete();
   
       return redirect()->route('admin.departments.all')
           ->with('success', 'Department deleted successfully!');
   }
}
