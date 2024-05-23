<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Adminauth extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['phone', 'password'];

    protected $hidden = ['password'];
}
