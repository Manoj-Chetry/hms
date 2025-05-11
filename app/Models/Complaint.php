<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    public $timestamps = false;

    protected $table = 'complaints';

    protected $fillable = [
        'student_id',
        'hostel_id',
        'issue_description',
        'status',
        'created_at',
        'resolved_at',
        'type'
    ];
}

