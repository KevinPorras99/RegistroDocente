<?php

namespace App\Http\Controllers;

use App\Models\ExamGrade;
use App\Models\Exam;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\User;

class ExamGradeController extends Controller
{
    public function showAddGradesForm($courseId, $cycle)
    {
        $course = Course::findOrFail($courseId);
        $students = $course->students; // Asumiendo que tienes una relación entre cursos y estudiantes
        $exams = Exam::where('course_id', $courseId)->where('cycle', $cycle)->get();
        $user = auth()->user(); // Obtener el usuario autenticado

        // Obtener las calificaciones existentes
        foreach ($exams as $exam) {
            $exam->grades = $exam->grades()->pluck('grade', 'student_id')->toArray();
        }

        return view('add-grades-exams', compact('course', 'students', 'exams', 'cycle', 'user'));
    }

    public function storeGrades(Request $request, $courseId)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.*' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->grades as $examId => $students) {
            foreach ($students as $studentId => $grade) {
                ExamGrade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'exam_id' => $examId,
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
