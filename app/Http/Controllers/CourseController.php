<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    
    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();

        $courses = $user->courses()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('grade', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%")
                    ->orWhere('classroom', 'like', "%{$search}%")
                    ->orWhere('cycle', 'like', "%{$search}%");
                });
            })
            ->paginate(5);

        $students = Student::all(); // Obtener todos los estudiantes

        return view('cursos', compact('courses', 'user', 'students'));
    }

    public function store(Request $request) {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'cycle' => 'required|string|in:Trimestre,Cuatrimestre,Semestre',
            'classroom' => 'required|string|max:255',
        ]);

        // Crear un nuevo curso y asociarlo con el usuario autenticado
        $course = new Course();
        $course->name = $request->input('name');
        $course->grade = $request->input('grade');
        $course->institution = $request->input('institution');
        $course->classroom = $request->input('classroom');
        $course->cycle = $request->input('cycle');
        $course->user_id = Auth::id(); // Asociar el curso con el usuario autenticado
        $course->save();

        // Redirigir a la lista de cursos con un mensaje de éxito
        return redirect()->route('courses.index')->with('success', 'Curso agregado exitosamente');
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'cycle' => 'required|string|in:Trimestre,Cuatrimestre,Semestre',
            'classroom' => 'required|string|max:255',
        ]);

        // Actualizar el curso
        $course->name = $request->input('name');
        $course->grade = $request->input('grade');
        $course->institution = $request->input('institution');
        $course->classroom = $request->input('classroom');
        $course->cycle = $request->input('cycle');
        $course->save();

        // Redirigir a la lista de cursos con un mensaje de éxito
        return redirect()->route('courses.index')->with('success', 'Curso actualizado exitosamente');
    }

    public function edit($id){
        $course = Course::findOrFail($id);
        return view('courses.edit', compact('course'));
    }

    public function destroy($id) {
        $course = Course::find($id);
        if ($course) {
            $course->delete();
        }
        return redirect()->route('courses.index');
    }

    public function assignStudents(Request $request)
    {
    $courseId = $request->input('course_id');
    $studentIds = $request->input('student_ids', []);

    $course = Course::find($courseId);
    $course->students()->sync($studentIds);

    return redirect()->route('courses.index')->with('success', 'Estudiantes asignados correctamente.');
    }

    public function show($courseId)
    {
        $course = Course::find($courseId);
        $students = Student::all(); // Asumiendo que tienes una lista de todos los estudiantes

        return view('cursos', compact('course', 'students'));
    }

    public function getAssignedStudents($courseId)
    {
        $course = Course::find($courseId);
        $assignedStudents = $course->students; // Asumiendo que tienes una relación definida en el modelo Course

        return response()->json($assignedStudents);
    }

    public function removeStudent($courseId, $studentId)
    {
        $course = Course::find($courseId);
        $course->students()->detach($studentId);
        return response()->json(['success' => true]);
    }
}