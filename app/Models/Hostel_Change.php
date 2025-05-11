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
}
