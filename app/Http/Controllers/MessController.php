<?php

namespace App\Http\Controllers;

use App\Models\MessDue;
use App\Models\MessExpense;
use Illuminate\Http\Request;
use App\Models\Seat;
use App\Models\Student;

class MessController extends Controller
{
    public function generalFeeList(Request $request){
       $seat = auth('student')->user()->seat_id;
       $hostel = Seat::where('id', $seat)->first()->hostel_id;

       $Students = Student::whereHas('seat', function($query) use ($hostel) {
                            $query->where('hostel_id', $hostel);
                        })->pluck('roll_number');

       $messExpenses = MessExpense::where('hostel_id', $hostel)->orderBy('starting_date', 'desc')->get();
        $request->expense_id ? $m = $request->expense_id : $m = $messExpenses->first()->id;
            
        $gen = MessDue::where('mess_expense_id',$m)->whereIn('student_id',$Students)->get();

        return view('student.mess.general', compact('gen','messExpenses'));
    }
    public function pendingList(){}
    public function doneList(){}
    public function collectFee(){}
    public function messExpense(Request $request){
        $seat = auth('student')->user()->seat_id;
        $hostel = Seat::where('id', $seat)->first()->hostel_id;

        $request->validate([
            'expense'=> 'required'
        ]);

        $data = [
            'starting_date' => $request->startDate,
            'end_date' => $request->endDate,
            'hostel_id' => $hostel,
            'expense' => $request->expense
        ];

        if(MessExpense::create($data)){
            return redirect()->route('student.dashboard')
                ->with('success', 'Expense Updated Successfully!');
        }else{
             return redirect()->route('student.dashboard')
            ->withErrors('errror', 'Failed Update Expense!');
        }
    }
}
