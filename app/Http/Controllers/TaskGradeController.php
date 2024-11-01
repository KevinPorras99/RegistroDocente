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

        // Obtener las calificaciones existentes y los porcentajes obtenidos
        foreach ($tasks as $task) {
            $task->grades = $task->grades()->pluck('grade', 'student_id')->toArray();
            $task->percentages = $task->grades()->pluck('percentage_obtained', 'student_id')->toArray();
        }

        // Calcular el porcentaje total obtenido por cada estudiante
        $studentPercentages = [];
        foreach ($students as $student) {
            $totalPercentage = 0;
            foreach ($tasks as $task) {
                if (isset($task->percentages[$student->id])) {
                    $totalPercentage += $task->percentages[$student->id];
                }
            }
            $studentPercentages[$student->id] = $totalPercentage;
        }

        return view('add-grades-tasks', compact('course', 'students', 'tasks', 'cycle', 'user', 'studentPercentages'));
    }
    

    
    public function storeGrades(Request $request, $courseId)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.*' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->grades as $studentId => $tasks) {
            foreach ($tasks as $taskId => $grade) {
                $task = Task::findOrFail($taskId);
                $percentageObtained = ($grade / 100) * $task->percentage;

                TaskGrade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'task_id' => $taskId,
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
}