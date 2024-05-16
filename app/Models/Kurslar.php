<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurslar extends Model
{
    protected $fillable = ['teachers_name', 'teachers_img', 'courses_name'];
    protected $table = 'kurslar';
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function lessons()
    {
        return $this->hasManyThrough(Lessons::class, Category::class);
    }
}
