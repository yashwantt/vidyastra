<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    //
    public $fillable = [
        'doa',
        'session',
        'type',
        'admsNumber',
        'class',
        'section',
        'subject',
        'subjectSections',
        'prevClassResult',
        'slcData',
        'slcRemarks',
        'issueDate',
        'slcFileNo',
        'admsStatus',
        'student_id',
        'user_id'
    ];
} 
