<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'employment_status','marital_status','dob', 'state_of_origin', 'nationality','state','city','street'
    ];
}
