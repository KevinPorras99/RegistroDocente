<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Justification extends Model
{
    use HasFactory;

    protected $fillable = [
        'assistance_id',
        'file_path',
        'observations',
        'justification_status',
    ];

    public function assistance()
    {
        return $this->belongsTo(Assistance::class);
    }

    public function course()
    {
        return $this->hasOneThrough(Course::class, Assistance::class, 'id', 'id', 'assistance_id', 'course_id');
    }
}