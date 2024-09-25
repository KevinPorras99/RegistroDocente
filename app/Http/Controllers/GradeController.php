<?php

namespace App\Http\Controllers;

use App\Models\Grade;
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

        // Verificar los datos
        dd(compact('course', 'students', 'dailyWorks', 'cycle', 'user'));

        return view('add-grades', compact('course', 'students', 'dailyWorks', 'cycle', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'grades' => 'required|array',
            'grades.*' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->grades as $dailyWorkId => $score) {
            Grade::create([
                'student_id' => $request->student_id,
                'subject' => DailyWork::find($dailyWorkId)->name,
                'type' => 'Trabajo Cotidiano',
                'score' => $score,
            ]);
        }

        return redirect()->back()->with('success', 'Calificaciones guardadas exitosamente.');
    }
}