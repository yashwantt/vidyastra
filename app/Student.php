<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
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
        'status',
        'cwsn',
        'user_id',
    ];
}
