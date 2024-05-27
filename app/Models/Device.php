<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'lastname',
        'firstname',
        'userimg',
        'androidId',
        'windowsId',
        'token',
    ];

    public function kurslars()
    {
        return $this->belongsToMany(Kurslar::class, 'device_kurslar');
    }


}


