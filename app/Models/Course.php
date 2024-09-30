<?php
// app/Models/Course.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'grade', 'institution', 'classroom', 'cycle',
        'daily_work_percentage', 'exam_percentage', 'assignment_percentage',
        'conduct_percentage', 'attendance_percentage', 'user_id'
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function dailyWorks()
    {
        return $this->hasMany(DailyWork::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
