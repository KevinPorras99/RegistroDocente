<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyWorkGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_work_id',
        'student_id',
        'grade',
    ];
}
