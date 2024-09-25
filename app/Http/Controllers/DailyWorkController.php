<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyWork;
use App\Models\Course;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\DailyWorkGrade;

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

        $dailyWorks = DailyWork::with('course')
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
            ->paginate(5);

        $courses = Course::all();

        $students = [];
        if ($courseId) {
            $course = Course::with('students')->find($courseId);
            if ($course) {
                $students = $course->students;
            }
        }

        return view('trabajocotidiano', compact('dailyWorks', 'user', 'courses', 'students', 'courseId', 'cycle'));
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
            'file' => 'nullable|file|mimes:pdf,txt,doc,docx',
        ]);

        $course = Course::findOrFail($request->course_id);
        $totalPercentage = $course->dailyWorks()->where('cycle', $request->cycle)->sum('percentage');
        $allowedPercentage = $course->daily_work_percentage - $totalPercentage;

        if ($request->percentage > $allowedPercentage) {
            return back()->withErrors(['percentage' => 'El porcentaje no puede exceder el porcentaje permitido para el curso.'])->withInput();
        }

        // Crear el nuevo trabajo cotidiano
        $dailyWork = new DailyWork();
        $dailyWork->name = $request->name;
        $dailyWork->description = $request->description;
        $dailyWork->due_date = $request->due_date;
        $dailyWork->course_id = $request->course_id;
        $dailyWork->cycle = $request->cycle;
        $dailyWork->percentage = $request->percentage;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('daily_works');
            $dailyWork->file_path = $filePath;
        }

        $dailyWork->save();

        

        return redirect()->route('dailyWorks.index')->with('success', 'Trabajo cotidiano agregado exitosamente.');
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
            'percentage' => 'required|integer|min:0',
            'file' => 'nullable|file|mimes:pdf,txt,doc,docx|max:2048',
        ]);

        $course = Course::findOrFail($request->course_id);
        $totalPercentage = $course->dailyWorks()->where('cycle', $request->cycle)->where('id', '!=', $id)->sum('percentage');
        $allowedPercentage = $course->daily_work_percentage - $totalPercentage;

        if ($request->percentage > $allowedPercentage) {
            return back()->withErrors(['percentage' => 'El porcentaje no puede exceder el porcentaje permitido para el curso.'])->withInput();
        }

        $dailyWork->name = $request->input('name');
        $dailyWork->description = $request->input('description');
        $dailyWork->due_date = $request->input('due_date');
        $dailyWork->course_id = $request->input('course_id');
        $dailyWork->cycle = $request->input('cycle');
        $dailyWork->percentage = $request->input('percentage');

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

    public function showAddGradesForm($courseId, $cycle)
    {
        $course = Course::findOrFail($courseId);
        $students = $course->students;
        $dailyWorks = DailyWork::where('course_id', $courseId)->where('cycle', $cycle)->get();
        $user = auth()->user();

        foreach ($dailyWorks as $dailyWork) {
            $dailyWork->grades = $dailyWork->grades()->pluck('grade', 'student_id')->toArray();
        }

        return view('add-grades', compact('course', 'students', 'dailyWorks', 'cycle', 'user'));
    }

    public function storeGrades(Request $request, $courseId)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'nullable|integer|min:0|max:100',
        ]);

        foreach ($request->grades as $dailyWorkId => $grades) {
            foreach ($grades as $studentId => $grade) {
                DailyWorkGrade::updateOrCreate(
                    ['daily_work_id' => $dailyWorkId, 'student_id' => $studentId],
                    ['grade' => $grade]
                );
            }
        }

        return redirect()->route('courses.show', $courseId)->with('success', 'Calificaciones actualizadas exitosamente.');
    }

    public function getDailyWorks($courseId, $cycle)
    {
        $dailyWorks = DailyWork::where('course_id', $courseId)->where('cycle', $cycle)->get();
        return response()->json(['dailyWorks' => $dailyWorks]);
    }

    public function showDailyTasks($courseId, $cycle)
    {
        $course = Course::findOrFail($courseId);
        $students = $course->students;
        $user = Auth::user();

        return view('trabajocotidiano', compact('course', 'students', 'courseId', 'cycle', 'user'));
    }

    public function getAllowedPercentage(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $cycle = $request->query('cycle');
        $totalPercentage = $course->daily_work_percentage; // Suponiendo que `daily_work_percentage` es el campo que almacena el porcentaje total permitido
        $usedPercentage = $course->dailyWorks()->where('cycle', $cycle)->sum('percentage');
        $allowedPercentage = $totalPercentage - $usedPercentage;

        return response()->json([
            'allowedPercentage' => $allowedPercentage,
            'totalPercentage' => $totalPercentage
        ]);
    }

    public function getDailyWorksByCourseAndCycle($courseId, $cycle)
    {
        $dailyWorks = DailyWork::where('course_id', $courseId)->where('cycle', $cycle)->get();
        return response()->json(['dailyWorks' => $dailyWorks]);
    }
}