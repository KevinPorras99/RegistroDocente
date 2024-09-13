<?php
// app/Models/DailyWork.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyWork extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'due_date'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
