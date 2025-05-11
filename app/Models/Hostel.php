<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    protected $table = 'hostels';

    public $timestamps = false;

    protected $fillable = ['name','gender','floors','number_of_rooms','number_of_seats'];
}
