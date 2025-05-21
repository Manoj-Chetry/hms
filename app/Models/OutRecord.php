<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutRecord extends Model
{
    protected $table = 'out_records';
    public $timestamps = false;
    protected $fillable = [
        'student_id',
        'out_date',
        'in_date'
    ];


    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

}
