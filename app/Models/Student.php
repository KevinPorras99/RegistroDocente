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

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function assistances()
    {
        return $this->hasMany(Assistance::class);
    }
    public function conducts()
    {
        return $this->hasMany(Conduct::class);
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificaciones::class);
    }

    public function dailyWorkGrades()
    {
        return $this->hasMany(DailyWorkGrade::class);
    }

    public function taskGrades()
    {
        return $this->hasMany(TaskGrade::class);
    }
}
