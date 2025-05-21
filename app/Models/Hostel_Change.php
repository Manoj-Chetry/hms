<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hostel_Change extends Model
{
    protected $table = 'hostel_changes';
    public $timestamps = false;

    protected $fillable = [
        'student_id', 'destination_hostel_id', 'new_seat_id', 'status', 'created'
    ];


    public function student() {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function destinationHostel() {
        return $this->belongsTo(Hostel::class, 'destination_hostel_id');
    }
   
}
