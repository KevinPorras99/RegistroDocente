<?php
// app/Http/Controllers/ExamController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user(); // Obtener el usuario autenticado

        $exams = $user->exams()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%")
                             ->orWhere('due_date', 'like', "%{$search}%");
            })
            ->paginate(5); // Paginación con 5 registros por página

        return view('examenes', compact('exams', 'user'));
    }

    public function store(Request $request) {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,txt,doc,docx|max:2048',
        ]);

        // Crear un nuevo examen y asociarlo con el usuario autenticado
        $exam = new Exam();
        $exam->name = $request->input('name');
        $exam->description = $request->input('description');
        $exam->due_date = $request->input('due_date');
        $exam->user_id = Auth::id(); // Asociar el examen con el usuario autenticado
        // Manejar la subida de archivos
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('exams', 'public');
            $exam->file_path = $path;
        }
        $exam->save();


        // Redirigir a la lista de exámenes con un mensaje de éxito
        return redirect()->route('exams.index')->with('success', 'Examen agregado exitosamente');
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,txt,doc,docx|max:2048',
        ]);

        // Actualizar el examen
        $exam->name = $request->input('name');
        $exam->description = $request->input('description');
        $exam->due_date = $request->input('due_date');

        // Manejar la subida de archivos
        if ($request->hasFile('file')) {
            // Eliminar el archivo anterior si existe
            if ($exam->file_path) {
                Storage::disk('public')->delete($exam->file_path);//Undefined type 'App\Http\Controllers\Storage'
            }

            $file = $request->file('file');
            $path = $file->store('exams', 'public');
            $exam->file_path = $path;
    }
        $exam->save();

        // Redirigir a la lista de exámenes con un mensaje de éxito
        return redirect()->route('exams.index')->with('success', 'Examen actualizado exitosamente');
    }

    public function edit($id){
        $exam = Exam::findOrFail($id);
        return view('exams.edit', compact('exam'));
    }

    public function destroy($id) {
        $exam = Exam::find($id);
        if ($exam) {
            $exam->delete();
        }
        return redirect()->route('exams.index');
    }
}
