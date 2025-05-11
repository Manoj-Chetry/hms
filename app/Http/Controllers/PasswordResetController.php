<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('email');
    }


    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        // Determine user type based on email
        $user = Student::where('email', $email)->first();
        $broker = 'students';

        if (!$user) {
            $user = Staff::where('email', $email)->first();
            $broker = 'staffs';
        }

        if (!$user) {
            $user = Admin::where('email', $email)->first();
            $broker = 'admins';
        }

        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Generate token manually
        $token = Str::random(60);

        // Insert the reset request manually to include 'user_type'
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => bcrypt($token),
            'user_type' => $broker,  // Store the user type
            'created_at' => now(),
        ]);

        // Send the reset link
        $status = Password::broker($broker)->sendResetLink(['email' => $email]);

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

}
