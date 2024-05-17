<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'androidId',
        'windowsId',
        'kurslar_id',
    ];

    public function kurslar()
    {
        return $this->belongsTo(Kurslar::class);
    }
}
