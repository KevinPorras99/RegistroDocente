<?php

namespace App\Http\Controllers;

use App\Models\TaskGrade;
use App\Models\Task;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\User;

class TaskGradeController extends Controller
{
    public function showAddGradesForm($courseId, $cycle)
    {
        $course = Course::findOrFail($courseId);
        $students = $course->students; // Asumiendo que tienes una relación entre cursos y estudiantes
        $tasks = Task::where('course_id', $courseId)->where('cycle', $cycle)->get();
        $user = auth()->user(); // Obtener el usuario autenticado

        // Obtener las calificaciones existentes
        foreach ($tasks as $task) {
            $task->grades = $task->grades()->pluck('grade', 'student_id')->toArray();
        }

        return view('add-grades-tasks', compact('course', 'students', 'tasks', 'cycle', 'user'));
    }

    public function storeGrades(Request $request, $courseId)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.*' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->grades as $studentId => $tasks) {
            foreach ($tasks as $taskId => $grade) {
                // Verificar que el task_id exista en la tabla tasks
                if (!Task::where('id', $taskId)->exists()) {
                    // Omitir esta tarea y continuar con las demás
                    continue;
                }

                TaskGrade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'task_id' => $taskId,
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