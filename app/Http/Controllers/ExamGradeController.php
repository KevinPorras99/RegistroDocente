<?php

namespace App\Http\Controllers;

use App\Models\ExamGrade;
use App\Models\Exam;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\User;

class ExamGradeController extends Controller
{
    public function storeGrades(Request $request, $courseId)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.*' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->grades as $examId => $students) {
            foreach ($students as $studentId => $grade) {
                $exam = Exam::findOrFail($examId);
                $percentageObtained = ($grade / 100) * $exam->percentage;

                ExamGrade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'exam_id' => $examId,
                    ],
                    [
                        'grade' => $grade,
                        'percentage_obtained' => $percentageObtained,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Calificaciones guardadas exitosamente.');
    }

    public function showAddGradesForm($courseId, $cycle)
    {
        $course = Course::findOrFail($courseId);
        $students = $course->students;
        $exams = Exam::where('course_id', $courseId)->where('cycle', $cycle)->get();
        $user = auth()->user();

        foreach ($exams as $exam) {
            $exam->grades = $exam->grades()->pluck('grade', 'student_id')->toArray();
            $exam->percentages = $exam->grades()->pluck('percentage_obtained', 'student_id')->toArray();
        }

        // Calcular el porcentaje total obtenido por cada estudiante
        $studentPercentages = [];
        foreach ($students as $student) {
            $totalPercentage = 0;
            foreach ($exams as $exam) {
                if (isset($exam->percentages[$student->id])) {
                    $totalPercentage += $exam->percentages[$student->id];
                }
            }
            $studentPercentages[$student->id] = $totalPercentage;
        }

        return view('add-grades-exams', compact('course', 'students', 'exams', 'cycle', 'user', 'studentPercentages'));
    }
}
