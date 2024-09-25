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

    public function course()
    {
        return $this->belongsToMany(Course::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function dailyWorks()
    {
        return $this->belongsToMany(DailyWork::class, 'student_daily_work', 'student_id', 'daily_work_id');
    }
}