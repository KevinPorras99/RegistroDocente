<?php
// app/Http/Controllers/StudentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index() {
        $students = Student::all();
        $user = Auth::user(); // Obtener el usuario autenticado
        return view('estudiantes', compact('students', 'user'));
    }

    //public function estudiantes() {
    //    $user = Auth::user(); // Obtén el usuario autenticado
    //    $students = $user->students; // Asumiendo que el usuario tiene una relación con los estudiantes
    //    return view('estudiantes', compact('user', 'students')); // Pasa el usuario y los estudiantes a la vista
    //}

    public function store(Request $request) {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'section' => 'required|string|max:255',
        ]);

        // Crear un nuevo estudiante
        $student = new Student();
        $student->name = $request->input('name');
        $student->grade = $request->input('grade');
        $student->institution = $request->input('institution');
        $student->section = $request->input('section');
        $student->save();

        // Redirigir a la lista de estudiantes con un mensaje de éxito
        return redirect()->route('students.index')->with('success', 'Estudiante agregado exitosamente');
    }

    public function destroy($id) {
        $student = Student::find($id);
        if ($student) {
            $student->delete();
        }
        return redirect()->route('students.index');
    }
}