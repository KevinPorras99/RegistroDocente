<?php
// app/Models/Course.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'grade', 'institution', 'classroom', 'cycle',];

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
}
