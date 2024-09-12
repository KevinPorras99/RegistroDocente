<?php
// app/Models/Student.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'grade',
        'institution',
        'section',
        'user_id',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}