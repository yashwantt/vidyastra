<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Academic extends Model
{
    //
    public $fillable = [
        'academicData',
        'admission_id',
        'student_id',
        'user_id'
    ];
}
