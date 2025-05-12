<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CaretakerController;
use App\Http\Controllers\DswController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\WardenController;
use App\Http\Controllers\PasswordResetController;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Hostel;



use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
    Mail::raw('Test email content', function ($message) {
        $message->to('mchetry606@example.com')
                ->subject('Test Email');
    });
    return 'Test email sent!';
});


// use Illuminate\Support\Facades\Log;

// Route::middleware(['web'])->get('/test', function () {
//     session(['mytest' => 'hello']);

//     Log::info('Session Data: ', session()->all());

//     return 'Done';
// });




// 🏠 Home
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// 🔐 Login Forms
Route::get('/login/admin', function () {
    return view('login.admin');
})->name('admin.login.form');

Route::get('/login/staff', function () {
    return view('login.staff');
})->name('staff.login.form');

Route::get('/login/student', function () {
    return view('login.student');
})->name('student.login.form');


// Reset Password
Route::get('forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');

Route::middleware(['session_expiry'])->group(function(){

    // 🔑 Admin Login Handler (POST)
    Route::post('/login/admin', [AuthController::class, 'AdminLogin'])->name('admin.login.post');

    // 🧠 Admin Protected Routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // logout
        Route::post('/admin/logout', function () {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login.form');
        })->name('admin.logout');


        // staff fetch all
        Route::get('/admin/staffs', [AdminController::class, 'staffFetchAll'])->name('admin.staffs.all');

        Route::get('admin/staff/edit/{id}', [AdminController::class, 'editStaff'])->name('admin.staff.edit');
        Route::delete('admin/staff/delete/{id}', [AdminController::class, 'deleteStaff'])->name('admin.staff.delete');

        // staff add
        Route::post('/admin/staff/add', [AdminController::class, 'addStaff'])->name('admin.staff.add');


        // student add csv
        Route::post('/admin/upload', [AdminController::class, 'uploadCSV'])->name('admin.student_upload');

        // departments view fetch
        Route::get('/admin/departments', function(){
            $departments = Department::all();
            return view('admin.departments', compact('departments'));
        })->name('admin.departments.all');

        // Add, update, delete Department
        Route::post('admin/departments/add',[AdminController::class, 'addDepartment'])->name('admin.department.add');
        Route::get('admin/departments/edit/{id}', [AdminController::class, 'editStaff'])->name('admin.department.edit');
        Route::delete('admin/departments/delete/{id}', [AdminController::class, 'deleteDepartment'])->name('admin.department.delete');
    });

    // Student Login Handler
    Route::post("/login/student", [AuthController::class, 'StudentLogin'])->name('student.login.post');

    Route::middleware('auth:student')->group(function(){
        Route::get("/student/dashboard", [StudentController::class, 'viewDashboard'])->name("student.dashboard");                                                            

        Route::post('/student/logout', function () {
            Auth::guard('student')->logout();
            return redirect()->route('student.login.form');
        })->name('student.logout');

        //myActions page
        Route::get('student/myActions', [StudentController::class, 'myActions'])->name('student.myActions');

        //Ragging and general complaint
        Route::post('/student/lodge_complaint', [StudentController::class, 'makeComplaint'])->name('student.makeComplaint');
        Route::post('/student/ragging_complaint', [StudentController::class, 'makeRaggingComplaint'])->name('student.makeRaggingComplaint');

        // hostel change
        Route::post('/student/hostel_change', [StudentController::class, 'hostelChange'])->name('student.hostelChange');

        // leave request
        Route::post("/student/apply_leave",[StudentController::class, 'applyLeave'])->name("student.applyLeave");
    });

    // Staff Login Handler
    Route::post("/login/staff", [AuthController::class, 'StaffLogin'])->name('staff.login.post');

    Route::prefix('staff')->name('staff.')->group(function () {

        Route::middleware('auth:staff')->group(function () {
            
            // DSW Dashboard
            Route::get('dsw/dashboard', function() {
                $hostels = Hostel::all();
                return view('staff.dsw_dashboard', compact('hostels'));
            })->name('dsw.dashboard');
            
            // Warden Dashboard
            Route::get('warden/dashboard', [WardenController::class, 'viewDashboard'])->name('warden.dashboard');
            
            // Caretaker Dashboard
            Route::get('caretaker/dashboard', function() {
                $caretaker = auth('staff')->user(); 
                $hostel = Hostel::find($caretaker->hostel_id); 
                $roles = Role::where('hostel_id', $caretaker->hostel_id)->get();

                return view('staff.caretaker_dashboard', compact('hostel', 'roles'));
            })->name('caretaker.dashboard');
            
            // Attender Dashboard
            Route::get('attender/dashboard', function() {
                $attender = auth()->user(); // Get the logged-in attender
                $hostel = Hostel::find($attender->hostel_id); // Fetch the hostel associated with the attender
                
                return view('staff.attender_dashboard', compact('hostel'));
            })->name('attender.dashboard');
            
            // HOD Dashboard
            Route::get('hod/dashboard', function() {
                $hod = auth()->user(); // Get the logged-in HOD
                $hostel = Hostel::find($hod->hostel_id); // Fetch the hostel associated with the HOD (assuming HOD has hostel_id)
                
                return view('staff.hod_dashboard', compact('hostel'));
            })->name('hod.dashboard');
    
    
            Route::post('/staff/logout', function () {
                Auth::guard('staff')->logout();
                return redirect()->route('staff.login.form');
            })->name('logout');

            Route::post('dsw/addHostel', [DswController::class, 'addHostel'])->name('dsw.addHostel');

            Route::get('dsw/hostels/{id}', function ($id) {
                $hostel = Hostel::findOrFail($id);
                return view('staff.dsw._hostel_details', compact('hostel')); // updated path
            });

            // Student Details : caretaker
            Route::get('/caretaker/student_details', [CaretakerController::class, 'studentDetails'])->name("caretaker.student_details");

            // Room details : caretaker
            Route::get('/caretaker/room_details', [CaretakerController::class, 'roomDetails'])->name("caretaker.room_details");

            // Add rooms by caretaker : caretaker
            Route::post('/caretaker/add_single_room', [CaretakerController::class, 'addSingleRoom'])->name('caretaker.add_single_room');
            Route::post('/caretaker/add_multiple_room', [CaretakerController::class, 'addMultipleRoom'])->name('caretaker.add_multiple_room');

            // View General Complaints : Caretaker
            Route::get('/caretaker/view_general_complaints', [CaretakerController::class,'viewGeneralComplaints'])->name('caretaker.view.general_complaints');
            // Resolve complaint
            Route::post('/caretaker/complaint_resolve/{id}', [CaretakerController::class, 'resolveComplaint'])->name('caretaker.complaint.resolve');


            // hostel view: dsw
            Route::get('dsw/hostel/{name}', [DswController::class, 'viewHostel'])->name('dsw.hostel');


            // Student Details : warden
            Route::get('/warden/student_details', [WardenController::class, 'studentDetails'])->name("warden.student_details");
            Route::get('warden/student_search', [WardenController::class, 'studentSearch'])->name('students.index');

            // Role assign : warden
            Route::post('warden/assign_role', [WardenController::class, 'assignRole'])->name('warden.assignRole');
        });
    });

});