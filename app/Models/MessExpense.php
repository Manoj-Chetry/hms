<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessExpense extends Model
{
    protected $table = 'mess_expenses';

    public $timestamps = false;
    protected $fillable = [
        'hostel_id', 'starting_date', 'end_date', 'expense'
    ];
}
