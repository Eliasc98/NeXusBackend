<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Model
{
    //
    protected $guard = 'admin';
    
    protected $fillable = [        
        'fullname', 
        'email', 
        'password', 
        'username'
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];
}
