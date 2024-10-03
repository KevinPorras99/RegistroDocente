<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Course;
use App\Models\TaskGrade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        $courseId = $request->input('course');
        $cycle = $request->input('cycle');
        $user = Auth::user();

        $tasks = Task::with('course')
            ->whereHas('course', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('due_date', 'like', "%{$search}%");
            })
            ->when($courseId, function ($query, $courseId) {
                return $query->where('course_id', $courseId);
            })
            ->when($cycle, function ($query, $cycle) {
                return $query->where('cycle', $cycle);
            })
            ->paginate(5); // Paginación con 5 registros por página

        $courses = Course::all();

        $students = [];
        if ($courseId) {
            $course = Course::with('students')->find($courseId);
            if ($course) {
                $students = $course->students;
            }
        }

        return view('tareasyasignaciones', compact('tasks', 'user', 'courses', 'students', 'courseId', 'cycle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'course_id' => 'required|integer',
            'cycle' => 'required|string',
            'percentage' => 'required|integer|min:0',
            'file' => 'nullable|file|mimes:pdf,txt,doc,docx|max:2048',
        ]);

        $course = Course::findOrFail($request->course_id);
        $totalPercentage = $course->tasks()->where('cycle', $request->cycle)->sum('percentage');
        $allowedPercentage = $course->assignment_percentage - $totalPercentage;

        if ($request->percentage > $allowedPercentage) {
            return back()->withErrors(['percentage' => 'El porcentaje no puede exceder el porcentaje permitido para el curso.'])->withInput();
        }

        $task = new Task();
        $task->name = $request->name;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->course_id = $request->course_id;
        $task->cycle = $request->cycle;
        $task->percentage = $request->percentage;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('tasks', 'public');
            $task->file_path = $filePath;
        }

        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Tarea agregada exitosamente.');
    }


    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date',
            'course_id' => 'required|exists:courses,id',
            'cycle' => 'required|string',
            'percentage' => 'required|integer|min:0',
            'file' => 'nullable|file|mimes:pdf,txt,doc,docx|max:2048',
        ]);

        $course = Course::findOrFail($request->course_id);
        $totalPercentage = $course->tasks()->where('cycle', $request->cycle)->where('id', '!=', $id)->sum('percentage');
        $allowedPercentage = $course->assignment_percentage - $totalPercentage;

        if ($request->percentage > $allowedPercentage) {
            return back()->withErrors(['percentage' => 'El porcentaje no puede exceder el porcentaje permitido para el curso.'])->withInput();
        }

        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->due_date = $request->input('due_date');
        $task->course_id = $request->input('course_id');
        $task->cycle = $request->input('cycle');
        $task->percentage = $request->input('percentage');

        if ($request->hasFile('file')) {
            if ($task->file_path) {
                Storage::disk('public')->delete($task->file_path);
            }

            $file = $request->file('file'); // Definir la variable $file
            $path = $file->store('tasks', 'public');
            $task->file_path = $path;
        }

        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Tarea actualizada exitosamente');
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->delete();
        }
        return redirect()->route('tasks.index');
    }

    

    public function getTasks($courseId, $cycle)
    {
        $tasks = Task::where('course_id', $courseId)->where('cycle', $cycle)->get();
        return response()->json(['tasks' => $tasks]);
    }

    public function showDailyTasks($courseId, $cycle)
    {
        $course = Course::findOrFail($courseId);
        $students = $course->students;
        $user = Auth::user();

        return view('tareasyasignaciones', compact('course', 'students', 'courseId', 'cycle', 'user'));
    }

    public function getAllowedPercentage(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $cycle = $request->query('cycle');
        $totalPercentage = $course->assignment_percentage; // Suponiendo que `assignment_percentage` es el campo que almacena el porcentaje total permitido
        $usedPercentage = $course->tasks()->where('cycle', $cycle)->sum('percentage');
        $allowedPercentage = $totalPercentage - $usedPercentage;

        return response()->json([
            'allowedPercentage' => $allowedPercentage,
            'totalPercentage' => $totalPercentage
        ]);
    }

    public function getTasksByCourseAndCycle($courseId, $cycle)
    {
        $tasks = Task::where('course_id', $courseId)->where('cycle', $cycle)->get();
        return response()->json(['tasks' => $tasks]);
    }
}
