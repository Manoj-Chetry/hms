<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttenderController;
use App\Http\Controllers\CaretakerController;
use App\Http\Controllers\DswController;
use App\Http\Controllers\HodController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\WardenController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\MessController;
use App\Http\Controllers\MessDuesController;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Hostel;
use App\Models\Hostel_Change;
use App\Models\Seat;
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
        Route::get('/student/profile/edit/{id}', [StudentController::class, 'editProfile'])->name('student.profile.edit');
    });

    Route::middleware(['auth:student', 'messconveynor'])->group(function () {
        Route::get('/student/mess/general_list', [MessController::class, 'generalFeeList'])->name('mess.general');
        Route::get('/student/mess/pending', [MessController::class, 'pendingList'])->name('mess.pending');
        Route::get('/student/mess/done', [MessController::class, 'doneList'])->name('mess.done');
        Route::post('/student/mess/collect', [MessController::class, 'collectFee'])->name('mess.collect');
        Route::post('/student/mess/expense', [MessController::class, 'messExpense'])->name('mess.expense');

        Route::post('/mess-dues/calculate', [MessDuesController::class, 'calculate'])->name('mess_dues.calculate');


    });

    // Staff Login Handler
    Route::post("/login/staff", [AuthController::class, 'StaffLogin'])->name('staff.login.post');

    Route::prefix('staff')->name('staff.')->group(function () {

        Route::middleware('auth:staff')->group(function () {

            // ⬇️ DSW-only routes
            Route::middleware('staff.role:dsw')->group(function () {
                Route::get('dsw/dashboard', [DswController::class, 'viewDashboard'])->name('dsw.dashboard');

                Route::post('dsw/addHostel', [DswController::class, 'addHostel'])->name('dsw.addHostel');

                Route::get('dsw/hostels/{id}', function ($id) {
                    $hostel = Hostel::findOrFail($id);
                    return view('staff.dsw._hostel_details', compact('hostel'));
                });

                Route::get('dsw/hostel/{name}', [DswController::class, 'viewHostel'])->name('dsw.hostel');

                Route::post('dsw/hostelChange/forward/{id}',[DswController::class, 'hostelForward'])->name('dsw.hostel.forward');
                Route::post('dsw/hostelChange/approve/{id}',[DswController::class, 'hostelApprove'])->name('dsw.hostel.approve');
                Route::post('dsw/hostelChange/reject/{id}',[DswController::class, 'hostelReject'])->name('dsw.hostel.reject');
            });

            // ⬇️ Warden-only routes
            Route::middleware('staff.role:warden')->group(function () {
                Route::get('warden/dashboard', [WardenController::class, 'viewDashboard'])->name('warden.dashboard');
                Route::get('/warden/student_details', [WardenController::class, 'studentDetails'])->name("warden.student_details");
                Route::get('warden/student_search', [WardenController::class, 'studentSearch'])->name('students.index');
                Route::post('warden/assign_role', [WardenController::class, 'assignRole'])->name('warden.assignRole');
                Route::get('/warden/view_general_complaints', [WardenController::class,'viewGeneralComplaints'])->name('warden.view.general_complaints');
                Route::post('/warden/complaint_resolve/{id}', [WardenController::class, 'resolveComplaint'])->name('warden.complaint.resolve');

                Route::get('warden/leave_requests', [WardenController::class, 'leaveRequest'])->name('warden.leaves');

                Route::post('warden/approve_leave/{id}',[WardenController::class, 'leaveApprove'])->name('warden.leave.approve');
                Route::post('warden/reject_leave/{id}',[WardenController::class, 'leaveReject'])->name('warden.leave.reject');

                Route::get('warden/hostelchange', function(){
                    $user = auth('staff')->user();
                    $hid = $user->hostel_id;
                    $hostelChangeOut = Hostel_Change::where('status', 'pending')
                        ->whereHas('student.hostel', function ($query) use ($hid) {
                            $query->where('hostels.id', $hid);
                        })
                        ->get();
                    $hostelChangeIn = Hostel_Change::where('status', 'forwarded:dsw')
                        ->where('destination_hostel_id',$hid)
                        ->get();

                    $vacant = Seat::where('hostel_id', $hid)->where('occupied', false)->get();

                    return view('staff.warden.hostelChange', compact('user','hostelChangeOut','hostelChangeIn', 'vacant'));
                })->name('warden.hostel.change');

                Route::post('warden/hostelChange/approve/{id}',[WardenController::class, 'hostelApprove'])->name('warden.hostel.approve');
                Route::post('warden/hostelChange/reject/{id}',[WardenController::class, 'hostelReject'])->name('warden.hostel.reject');

                Route::post('warden/hostelChangeIn/approve/{id}',[WardenController::class, 'hostelInApprove'])->name('warden.hostelIn.approve');

            });

            // ⬇️ Caretaker-only routes
            Route::middleware('staff.role:caretaker')->group(function () {
                Route::get('caretaker/dashboard', function() {
                    $caretaker = auth('staff')->user(); 
                    $hostel = Hostel::find($caretaker->hostel_id); 
                    $roles = Role::where('hostel_id', $caretaker->hostel_id)->get();
                    return view('staff.caretaker_dashboard', compact('hostel', 'roles'));
                })->name('caretaker.dashboard');

                Route::get('/caretaker/student_details', [CaretakerController::class, 'studentDetails'])->name("caretaker.student_details");
                Route::get('/caretaker/room_details', [CaretakerController::class, 'roomDetails'])->name("caretaker.room_details");
                Route::post('/caretaker/add_single_room', [CaretakerController::class, 'addSingleRoom'])->name('caretaker.add_single_room');
                Route::post('/caretaker/add_multiple_room', [CaretakerController::class, 'addMultipleRoom'])->name('caretaker.add_multiple_room');
                Route::get('/caretaker/view_general_complaints', [CaretakerController::class,'viewGeneralComplaints'])->name('caretaker.view.general_complaints');
                Route::post('/caretaker/complaint_resolve/{id}', [CaretakerController::class, 'resolveComplaint'])->name('caretaker.complaint.resolve');
            });

            // ⬇️ Attender-only routes
            Route::middleware('staff.role:attender')->group(function () {
                Route::get('attender/dashboard', [AttenderController::class, 'outRecord'])->name('attender.dashboard');

                Route::post('/attender/out_entry/{id}', [AttenderController::class, 'outEntry'])->name('attender.out.entry');
                Route::post('/attender/in_entry/{id}', [AttenderController::class, 'inEntry'])->name('attender.in.entry');
            });

            // ⬇️ HOD-only routes
            Route::middleware('staff.role:hod')->group(function () {
                Route::get('hod/dashboard', function() {
                    $hod = auth('staff')->user();
                    $dept = Department::where('id', $hod->department_id)->first()->name;
                    return view('staff.hod_dashboard', compact('hod','dept'));
                })->name('hod.dashboard');

                Route::get('hod/leave_requests', [HodController::class, 'leaveRequest'])->name('hod.leaves');

                Route::post('hod/approve_leave/{id}',[HodController::class, 'leaveApprove'])->name('hod.leave.approve');
                Route::post('hod/reject_leave/{id}',[HodController::class, 'leaveReject'])->name('hod.leave.reject');
            });

            // ⬇️ Logout route (common for all staff)
            Route::post('/staff/logout', function () {
                Auth::guard('staff')->logout();
                return redirect()->route('staff.login.form');
            })->name('logout');
        });
    });

});