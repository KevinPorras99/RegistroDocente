<?php
// app/Http/Controllers/DailyWorkController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyWork;
use App\Models\Course;
use App\Models\Grade; // Importar el modelo Grade
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
        $courseId = $request->input('course');
        $cycle = $request->input('cycle');
        $user = Auth::user();

        $dailyWorks = $user->dailyWorks()
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
            ->paginate(5);

        $courses = Course::all();

        $students = [];
        if ($courseId) {
            $course = Course::with('students')->find($courseId);
            if ($course) {
                $students = $course->students;
            }
        }

        return view('trabajocotidiano', compact('dailyWorks', 'user', 'courses', 'students', 'courseId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date',
            'course_id' => 'required|exists:courses,id',
            'cycle' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,txt,doc,docx|max:2048',
        ]);

        $dailyWork = new DailyWork();
        $dailyWork->name = $request->input('name');
        $dailyWork->description = $request->input('description');
        $dailyWork->due_date = $request->input('due_date');
        $dailyWork->course_id = $request->input('course_id');
        $dailyWork->cycle = $request->input('cycle');
        $dailyWork->user_id = Auth::id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('dailyWorks', 'public');
            $dailyWork->file_path = $path;
        }
        $dailyWork->save();

        return redirect()->route('dailyWorks.index')->with('success', 'Trabajo cotidiano agregado exitosamente');
    }

    public function update(Request $request, $id)
    {
        $dailyWork = DailyWork::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date',
            'course_id' => 'required|exists:courses,id',
            'cycle' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,txt,doc,docx|max:2048',
        ]);

        $dailyWork->name = $request->input('name');
        $dailyWork->description = $request->input('description');
        $dailyWork->due_date = $request->input('due_date');
        $dailyWork->course_id = $request->input('course_id');
        $dailyWork->cycle = $request->input('cycle');

        if ($request->hasFile('file')) {
            if ($dailyWork->file_path) {
                Storage::disk('public')->delete($dailyWork->file_path);
            }

            $file = $request->file('file');
            $path = $file->store('dailyWorks', 'public');
            $dailyWork->file_path = $path;
        }
        $dailyWork->save();

        return redirect()->route('dailyWorks.index')->with('success', 'Trabajo cotidiano actualizado exitosamente');
    }

    public function edit($id)
    {
        $dailyWork = DailyWork::findOrFail($id);
        return view('dailyWorks.edit', compact('dailyWork'));
    }

    public function destroy($id)
    {
        $dailyWork = DailyWork::find($id);
        if ($dailyWork) {
            $dailyWork->delete();
        }
        return redirect()->route('dailyWorks.index');
    }

    public function getCourseWorks($courseId, $studentId, Request $request)
    {
        $cycle = $request->input('cycle');

        $courseWorks = DailyWork::where('course_id', $courseId)
                                ->when($cycle, function ($query, $cycle) {
                                    return $query->where('cycle', $cycle);
                                })
                                ->get();
        return response()->json($courseWorks);
    }

    public function storeGrade(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'daily_work_id' => 'required|exists:daily_works,id',
            'grade' => 'required|numeric|min:0|max:100',
        ]);

        Grade::create([
            'student_id' => $request->input('student_id'),
            'course_id' => $request->input('course_id'),
            'daily_work_id' => $request->input('daily_work_id'),
            'grade' => $request->input('grade'),
        ]);

        return response()->json(['message' => 'Calificación guardada exitosamente']);
    }
}