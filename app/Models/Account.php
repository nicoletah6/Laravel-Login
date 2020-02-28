<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    protected $hidden = ['password', 'recovery_code'];
    protected $fillable = [
        'name', 'last_name', 'email', 'phone', 'password', 'recovery_code'
    ];
}
