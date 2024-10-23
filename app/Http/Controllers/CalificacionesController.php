<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Calificaciones;
use Illuminate\Http\Request;

class CalificacionesController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::all();
        return view('calificaciones', compact('courses'));
    }

    public function showGrades(Request $request)
    {
        $courses = Course::all();

        // Aplicar filtros de curso y ciclo
        $studentsQuery = Student::with(['courses', 'calificaciones' => function ($query) use ($request) {
            if ($request->has('course') && $request->course != '') {
                $query->where('course_id', $request->course);
            }
            if ($request->has('cycle') && $request->cycle != '') {
                $query->where('cycle', $request->cycle);
            }
        }]);

        if ($request->has('course') && $request->course != '') {
            $studentsQuery->whereHas('courses', function ($query) use ($request) {
                $query->where('courses.id', $request->course);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $studentsQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $students = $studentsQuery->paginate(10);

        return view('calificaciones', compact('courses', 'students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course' => 'required|exists:courses,id',
            'cycle' => 'required|string',
            'grade' => 'array',
            'grade.*' => 'nullable|integer|min:0|max:100',
        ]);

        $courseId = $data['course'];
        $cycle = $data['cycle'];

        foreach ($data['grade'] as $studentId => $grade) {
            $student = Student::find($studentId);

            Calificaciones::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'course_id' => $courseId,
                    'cycle' => $cycle,
                ],
                [
                    'grade' => $grade,
                ]
            );
        }

        return redirect()->back()->with('success', 'Calificaciones guardadas correctamente.');
    }
}
