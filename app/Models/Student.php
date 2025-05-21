<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'roll_number';
    public $incrementing = false;
    protected $fillable = [
        'name',
        'roll_number',
        'department',
        'department_id',
        'email',
        'phone',
        'seat_id',
        'seat',
        'password',
    ];

    public $timestamps = false;

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    public function hostel()
    {
        return $this->hasOneThrough(
            Hostel::class,
            Seat::class,
            'id',         // Foreign key on Seat table...
            'id',         // Foreign key on Hostel table...
            'seat_id',    // Local key on Student table...
            'hostel_id'   // Local key on Seat table...
        );
    }

    public function outrecords()
    {
        return $this->hasMany(OutRecord::class, 'student_id');
    }
}

