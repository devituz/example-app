<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lessons extends Model
{
    protected $fillable = ['video', 'title', 'description', 'category_id'];
    protected $table = 'lessons';
    // Lessons.php model
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


    public function kurslar()
    {
        return $this->belongsTo(Kurslar::class, 'kurslar_id', 'id');
    }
}
