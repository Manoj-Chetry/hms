<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $table = 'seats';

    public $timestamps = false;

    protected $fillable = ['id','seat','room_id','hostel_id','occupied'];

    public function room() {
        return $this->belongsTo(Room::class);
    }
}



