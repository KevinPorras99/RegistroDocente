<?php
namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'grades' => 'required|array',
            'grades.*' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->input('grades') as $workId => $gradeValue) {
            Grade::updateOrCreate(
                [
                    'student_id' => $request->input('student_id'),
                    'course_id' => $request->input('course_id'),
                    'daily_work_id' => $workId,
                ],
                [
                    'grade' => $gradeValue,
                ]
            );
        }

        return redirect()->back()->with('success', 'Calificaciones añadidas correctamente.');
    }
}