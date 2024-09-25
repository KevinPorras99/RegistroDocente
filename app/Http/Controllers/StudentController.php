<?php
// app/Http/Controllers/StudentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use App\Exports\StudentsTemplateExport;
use App\Models\Course;


class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    

    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user(); // Obtener el usuario autenticado

        $students = $user->students()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('grade', 'like', "%{$search}%")
                             ->orWhere('institution', 'like', "%{$search}%")
                             ->orWhere('section', 'like', "%{$search}%");
            })
            ->paginate(5); // Paginación con 5 registros por página

        return view('estudiantes', compact('students', 'user'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $student = new Student();
        $student->name = $request->name;
        $student->grade = $request->grade;
        $student->institution = $request->institution;
        $student->section = $request->section;
        $student->user_id = Auth::id(); // Asignar el usuario autenticado (docente)
        $student->course_id = $request->course_id; // Puede ser nulo
        $student->save();

        return redirect()->route('students.index')->with('success', 'Estudiante creado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'section' => 'required|string|max:255',
        ]);

        // Actualizar el estudiante
        $student->name = $request->input('name');
        $student->grade = $request->input('grade');
        $student->institution = $request->input('institution');
        $student->section = $request->input('section');
        $student->save();

        // Redirigir a la lista de estudiantes con un mensaje de éxito
        return redirect()->route('students.index')->with('success', 'Estudiante actualizado exitosamente');
    }

    public function edit($id){
    $student = Student::findOrFail($id);
    return view('students.edit', compact('student'));
    }

    public function destroy($id) {
        $student = Student::find($id);
        if ($student) {
            $student->delete();
        }
        return redirect()->route('students.index');
    }

    // Método para descargar la plantilla
    public function downloadTemplate()
    {
        return Excel::download(new StudentsTemplateExport, 'Plantilla_Estudiantes.xlsx');
    }

    // Carga del archivo Excel
    public function uploadExcel(Request $request)
    {
    $request->validate([
        'file' => 'required|mimes:xlsx'
    ]);

    $userId = Auth::id(); // Obtener el ID del usuario autenticado

    Excel::import(new StudentsImport($userId), $request->file('file'));

    return redirect()->route('students.index')->with('success', 'Estudiantes importados exitosamente.');
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('students.show', compact('student'));
    }

    public function create()
    {
        $courses = Course::all(); // Obtén todos los cursos
        return view('estudiantes', compact('courses'));
    }
}