<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Course;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\ExamGrade;

class ExamController extends Controller
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
        $user = Auth::user(); // Obtener el usuario autenticado

        $exams = Exam::with('course')
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

        return view('examenes', compact('exams', 'user', 'courses', 'students', 'courseId', 'cycle'));
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
        $totalPercentage = $course->exams()->where('cycle', $request->cycle)->sum('percentage');
        $allowedPercentage = $course->exam_percentage - $totalPercentage;

        if ($request->percentage > $allowedPercentage) {
            return back()->withErrors(['percentage' => 'El porcentaje no puede exceder el porcentaje permitido para el curso.'])->withInput();
        }

        // Crear el nuevo examen
        $exam = new Exam();
        $exam->name = $request->name;
        $exam->description = $request->description;
        $exam->due_date = $request->due_date;
        $exam->course_id = $request->course_id;
        $exam->cycle = $request->cycle;
        $exam->percentage = $request->percentage;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('exams');
            $path = $file->store('exams', 'public');
            $exam->file_path = $filePath;
        }

        $exam->save();

        return redirect()->route('exams.index')->with('success', 'Examen agregado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

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
        $totalPercentage = $course->exams()->where('cycle', $request->cycle)->where('id', '!=', $id)->sum('percentage');
        $allowedPercentage = $course->exam_percentage - $totalPercentage;

        if ($request->percentage > $allowedPercentage) {
            return back()->withErrors(['percentage' => 'El porcentaje no puede exceder el porcentaje permitido para el curso.'])->withInput();
        }

        $exam->name = $request->input('name');
        $exam->description = $request->input('description');
        $exam->due_date = $request->input('due_date');
        $exam->course_id = $request->input('course_id');
        $exam->cycle = $request->input('cycle');
        $exam->percentage = $request->input('percentage');

        if ($request->hasFile('file')) {
            if ($exam->file_path) {
                Storage::disk('public')->delete($exam->file_path);
            }

            $file = $request->file('file');
            $path = $file->store('exams', 'public');
            $exam->file_path = $path;
        }

        $exam->save();

        return redirect()->route('exams.index')->with('success', 'Examen actualizado exitosamente');
    }

    public function edit($id)
    {
        $exam = Exam::findOrFail($id);
        return view('exams.edit', compact('exam'));
    }

    public function destroy($id)
    {
        $exam = Exam::find($id);
        if ($exam) {
            $exam->delete();
        }
        return redirect()->route('exams.index');
    }

    public function showAddGradesForm($courseId, $cycle)
    {
        $course = Course::findOrFail($courseId);
        $students = $course->students;
        $exams = Exam::where('course_id', $courseId)->where('cycle', $cycle)->get();
        $user = auth()->user();

        foreach ($exams as $exam) {
            $exam->grades = $exam->grades()->pluck('grade', 'student_id')->toArray();
        }

        return view('add-grades-exams', compact('course', 'students', 'exams', 'cycle', 'user'));
    }

    public function getExams($courseId, $cycle)
    {
        $exams = Exam::where('course_id', $courseId)->where('cycle', $cycle)->get();
        return response()->json(['exams' => $exams]);
    }

    public function showExams($courseId, $cycle)
    {
        $course = Course::findOrFail($courseId);
        $students = $course->students;
        $user = Auth::user();

        return view('exams.index', compact('course', 'students', 'courseId', 'cycle', 'user'));
    }

    public function getAllowedPercentage(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $cycle = $request->query('cycle');
        $totalPercentage = $course->exam_percentage; // Suponiendo que `exam_percentage` es el campo que almacena el porcentaje total permitido
        $usedPercentage = $course->exams()->where('cycle', $cycle)->sum('percentage');
        $allowedPercentage = $totalPercentage - $usedPercentage;

        return response()->json([
            'allowedPercentage' => $allowedPercentage,
            'totalPercentage' => $totalPercentage
        ]);
    }

    public function getExamsByCourseAndCycle($courseId, $cycle)
    {
        $exams = Exam::where('course_id', $courseId)->where('cycle', $cycle)->get();
        return response()->json(['exams' => $exams]);
    }
}
