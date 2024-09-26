<?php

namespace App\Http\Controllers;

use App\Models\DailyWorkGrade;
use App\Models\DailyWork;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\User;

class GradeController extends Controller
{
    public function showAddGradesForm($courseId, $cycle)
    {
        $course = Course::findOrFail($courseId);
        $students = $course->students; // Asumiendo que tienes una relación entre cursos y estudiantes
        $dailyWorks = DailyWork::where('course_id', $courseId)->where('cycle', $cycle)->get();
        $user = auth()->user(); // Obtener el usuario autenticado

        // Obtener las calificaciones existentes
        foreach ($dailyWorks as $dailyWork) {
            $dailyWork->grades = $dailyWork->grades()->pluck('grade', 'student_id')->toArray();
        }

        return view('add-grades', compact('course', 'students', 'dailyWorks', 'cycle', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.*' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->grades as $studentId => $dailyWorkGrades) {
            foreach ($dailyWorkGrades as $dailyWorkId => $grade) {
                DailyWorkGrade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'daily_work_id' => $dailyWorkId,
                    ],
                    [
                        'grade' => $grade,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Calificaciones guardadas exitosamente.');
    }
}
