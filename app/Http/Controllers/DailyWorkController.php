<?php
// app/Http/Controllers/DailyWorkController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyWork;
use App\Models\Course; // Importar el modelo Course
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DailyWorkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user(); // Obtener el usuario autenticado

        $dailyWorks = $user->dailyWorks()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%")
                             ->orWhere('due_date', 'like', "%{$search}%");
            })
            ->paginate(5); // Paginación con 5 registros por página

        $courses = Course::all(); // Obtener todos los cursos

        return view('trabajocotidiano', compact('dailyWorks', 'user', 'courses'));
    }

    public function store(Request $request) {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,txt,doc,docx|max:2048',
        ]);

        // Crear un nuevo trabajo cotidiano y asociarlo con el usuario autenticado
        $dailyWork = new DailyWork();
        $dailyWork->name = $request->input('name');
        $dailyWork->description = $request->input('description');
        $dailyWork->due_date = $request->input('due_date');
        $dailyWork->user_id = Auth::id(); // Asociar el trabajo cotidiano con el usuario autenticado
        // Manejar la subida de archivos
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('dailyWorks', 'public');
            $dailyWork->file_path = $path;
        }
        $dailyWork->save();

        // Redirigir a la lista de trabajos cotidianos con un mensaje de éxito
        return redirect()->route('dailyWorks.index')->with('success', 'Trabajo cotidiano agregado exitosamente');
    }

    public function update(Request $request, $id)
    {
        $dailyWork = DailyWork::findOrFail($id);

        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,txt,doc,docx|max:2048',
        ]);

        // Actualizar el trabajo cotidiano
        $dailyWork->name = $request->input('name');
        $dailyWork->description = $request->input('description');
        $dailyWork->due_date = $request->input('due_date');

        // Manejar la subida de archivos
        if ($request->hasFile('file')) {
            // Eliminar el archivo anterior si existe
            if ($dailyWork->file_path) {
                Storage::disk('public')->delete($dailyWork->file_path);
            }

            $file = $request->file('file');
            $path = $file->store('dailyWorks', 'public');
            $dailyWork->file_path = $path;
        }
        $dailyWork->save();

        // Redirigir a la lista de trabajos cotidianos con un mensaje de éxito
        return redirect()->route('dailyWorks.index')->with('success', 'Trabajo cotidiano actualizado exitosamente');
    }

    public function edit($id){
        $dailyWork = DailyWork::findOrFail($id);
        return view('dailyWorks.edit', compact('dailyWork'));
    }

    public function destroy($id) {
        $dailyWork = DailyWork::find($id);
        if ($dailyWork) {
            $dailyWork->delete();
        }
        return redirect()->route('dailyWorks.index');
    }
}
