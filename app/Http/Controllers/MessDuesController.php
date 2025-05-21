<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MessExpense;
use App\Models\Student;
use App\Models\Role;
use App\Models\MessDue;
use Carbon\CarbonPeriod;

class MessDuesController extends Controller 
{
    public function calculate(Request $request)
    {
        $request->validate([
            'expense_id' => 'required|exists:mess_expenses,id',
        ]);

        $messExpense = MessExpense::findOrFail($request->expense_id);
        $hostelId = $messExpense->hostel_id;
        $startDate = $messExpense->starting_date;
        $endDate = $messExpense->end_date;

        DB::transaction(function () use ($messExpense, $hostelId, $startDate, $endDate) {
            // Exclude students with special roles
            $excludedStudentIds = Role::where('hostel_id',$hostelId)
                ->pluck('roll_number')
                ->toArray();

            logger()->info('Excluded Students:', $excludedStudentIds);

            // Get eligible students from this hostel
            $eligibleStudents = Student::whereHas('seat', function($query) use ($hostelId) {
                            $query->where('hostel_id', $hostelId);
                        })
                ->whereNotIn('roll_number', $excludedStudentIds)
                ->get();

            $totalDays = CarbonPeriod::create($startDate, $endDate)->count();
            $studentPresence = [];

            foreach ($eligibleStudents as $student) {
                $absentDays = 0;

                $outrecords = $student->outrecords()
                    ->where(function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('out_date', [$startDate, $endDate])
                            ->orWhereBetween('in_date', [$startDate, $endDate])
                            ->orWhere(function($q) use ($startDate, $endDate) {
                                $q->where('out_date', '<=', $startDate)
                                    ->where('in_date', '>=', $endDate);
                            });
                    })->get();

                foreach ($outrecords as $out) {
                    $from = max($startDate, $out->out_date);
                    $to = min($endDate, $out->in_date);
                    $absentDays += CarbonPeriod::create($from, $to)->count();
                }

                $presentDays = max(1, $totalDays - $absentDays); // Avoid division by zero
                $studentPresence[$student->roll_number] = [
                    'present' => $presentDays,
                    'absent' => $absentDays
                ];
            }

            $totalPresentDays = array_sum(array_column($studentPresence, 'present'));
            $expensePerDay = $totalPresentDays > 0 ? $messExpense->expense / $totalPresentDays : 0;

            // Optional: Delete existing dues for this expense (in case of recalculation)
            MessDue::where('mess_expense_id', $messExpense->id)->delete();

            foreach ($studentPresence as $studentId => $days) {
                MessDue::create([
                    'student_id' => $studentId,
                    'mess_expense_id' => $messExpense->id,
                    'present_days' => $days['present'],
                    'absent_days' => $days['absent'],
                    'amount' => round($days['present'] * $expensePerDay, 2),
                    'paid' => false,
                ]);
            }
        });


        return redirect()->back()->with('success', 'Mess dues calculated and saved successfully.');
    }
}
