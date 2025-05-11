<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;


class Staff extends Authenticatable
{
    protected $table = 'staffs';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'hostel_id',
    ];

    protected $guard = 'staffs';

    // public function department()
    // {
    //     return $this->belongsTo(Department::class);
    // }

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }
}
