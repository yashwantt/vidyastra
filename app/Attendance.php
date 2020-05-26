<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //
    public $fillable = [
        'atDate',
        'attendance',
        'notify',
        'admission_id',
        'student_id',
        'user_id'
    ];
}
