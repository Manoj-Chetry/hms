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
        'email',
        'phone',
        'seat_id',
        'seat',
        'password',
    ];

    public $timestamps = false;

    // Relationships
    // public function department()
    // {
    //     return $this->belongsTo(Department::class);
    // }

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }
}

