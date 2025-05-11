<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\Hostel;

class AuthController extends Controller
{

    public function AdminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return back()->withErrors(['email' => 'Admin user not found'])->withInput();
        }

        if ($request->password !== $admin->password) {
            return back()->withErrors(['password' => 'Incorrect password'])->withInput();
        }

        Auth::guard('admin')->login($admin);


        return redirect()->route('admin.dashboard');
    }

    public function StudentLogin(Request $request){
        $request->validate([
            'email' => 'email|required',
            'password' => 'required|min:6',
        ]);

        $student = Student::where('email', $request->email)->first();

        if (!$student) {
            return back()->withErrors(['email' => 'Student not found'])->withInput();
        }

        if ($request->password !== $student->password) {
            return back()->withErrors(['password' => 'Incorrect password'])->withInput();
        }

        Auth::guard('student')->login($student);

        return redirect()->route('student.dashboard');
    }

    public function StaffLogin(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required|min:6',
        ]);

        $staff = Staff::where('email', $request->email)->first();

        if (!$staff) {
            return back()->withErrors(['email' => 'Staff user not found'])->withInput();
        }

        if ($request->password !== $staff->password) {
            return back()->withErrors(['password' => 'Incorrect password'])->withInput();
        }

        Auth::guard('staff')->login($staff);

        return match ($staff->role) {
            'dsw'       => redirect()->route('staff.dsw.dashboard'),
            'warden'    => redirect()->route('staff.warden.dashboard'),
            'caretaker' => redirect()->route('staff.caretaker.dashboard'),
            'attender'  => redirect()->route('staff.attender.dashboard'),
            'hod'       => redirect()->route('staff.hod.dashboard'),
            default     => redirect()->route('staff.login.form')->withErrors(['role' => 'Unknown role.']),
        };
    }


}
