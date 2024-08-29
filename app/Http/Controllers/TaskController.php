<?php
// app/Http/Controllers/TaskController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user(); // Obtener el usuario autenticado

        $tasks = $user->tasks()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%")
                             ->orWhere('due_date', 'like', "%{$search}%");
            })
            ->paginate(5); // Paginación con 5 registros por página

        return view('tareasyasignaciones', compact('tasks', 'user'));
    }

    public function store(Request $request) {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date',
        ]);

        // Crear una nueva tarea y asociarla con el usuario autenticado
        $task = new Task();
        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->due_date = $request->input('due_date');
        $task->user_id = Auth::id(); // Asociar la tarea con el usuario autenticado
        $task->save();

        // Redirigir a la lista de tareas con un mensaje de éxito
        return redirect()->route('tasks.index')->with('success', 'Tarea agregada exitosamente');
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date',
        ]);

        // Actualizar la tarea
        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->due_date = $request->input('due_date');
        $task->save();

        // Redirigir a la lista de tareas con un mensaje de éxito
        return redirect()->route('tasks.index')->with('success', 'Tarea actualizada exitosamente');
    }

    public function edit($id){
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    public function destroy($id) {
        $task = Task::find($id);
        if ($task) {
            $task->delete();
        }
        return redirect()->route('tasks.index');
    }
}