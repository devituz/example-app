<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['category_name', 'category_img', 'kurslar_id'];
    protected $table = 'category';

    public function kurslar()
    {
        return $this->belongsTo(Kurslar::class, 'kurslar_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lessons::class, 'category_id');
    }
}
