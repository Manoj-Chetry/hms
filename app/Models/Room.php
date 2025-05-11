<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';

    public $timestamps = false;

    protected $fillable = ['id','capacity','room_number','hostel_id'];
}
