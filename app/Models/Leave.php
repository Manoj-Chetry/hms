<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table = 'leaves';

    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'reason',
        'deperature_time',
        'arrival_time',
        'status',
        'done'
    ];
}
