<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';

    protected $fillable = [
        'name',
        'age',
        'address',
        'telephone',
        'identity_number'
    ];

    public $timestamps = false;
}
