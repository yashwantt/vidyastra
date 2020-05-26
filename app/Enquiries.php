<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enquiries extends Model
{
    //
    public $fillable = [ 
        'uid', 
        'dob',
        'gender',
        'fullName',
        'father',
        'mother',
        'occupation',
        'income',
        'contact',
        'email',
        'addressLine',
        'country',
        'state',
        'city',
        'pinCode',
        'category',
        'subCategory',
        'religion',
        'locality',
        'bankName',
        'bankAccount',
        'bankIfsc',
        'bankBranch',
        'image',
        'attachments',
        'cwsn',
        'doa',
        'session',
        'type',
        'admsNumber',
        'class',
        'section',
        'subject',
        'subjectSections',
        'prevClassResult',
    ];
}
