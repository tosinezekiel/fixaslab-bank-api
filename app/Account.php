<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'user_id','account_no','account_name', 'account_type', 'available_balance'
    ];
}
