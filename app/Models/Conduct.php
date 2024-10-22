<?php
// app/Models/Conduct.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conduct extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'course_id', 'cycle', 'description'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function justifications()
    {
        return $this->hasMany(Justification::class);
    }
}
