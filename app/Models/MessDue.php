<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessDue extends Model
{
    protected $table = 'mess_dues';

    public $timestamps = false;
    protected $fillable = [
        'student_id',
        'mess_expense_id',
        'amount',
        'present_days',
        'absent_days',
        'paid',
    ];

    public function student_id()
    {
        return $this->belongsTo(Student::class);
    }

    public function messExpense()
    {
        return $this->belongsTo(MessExpense::class);
    }
}
