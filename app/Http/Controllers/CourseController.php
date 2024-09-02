<?php
// app/Http/Controllers/CourseController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user(); // Obtener el usuario autenticado

        $courses = $user->courses()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('grade', 'like', "%{$search}%")
                             ->orWhere('institution', 'like', "%{$search}%")
                             ->orWhere('classroom', 'like', "%{$search}%")
                             ->orWhere('cycle', 'like', "%{$search}%");
            })
            ->paginate(5); // Paginación con 5 registros por página

        return view('cursos', compact('courses', 'user'));
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
}
