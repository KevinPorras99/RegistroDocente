<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Conduct;
use App\Models\Justification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ConductController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::all();

        return view('conducta', compact('courses'));
    }

    public function showConduct(Request $request)
    {
        $courses = Course::all();
        $students = Student::with('conducts')->get();

        return view('conducta', compact('courses', 'students'));
    }

    public function storeGrades(Request $request)
    {
        $data = $request->validate([
            'conduct' => 'required|array',
            'conduct.*' => 'nullable|in:good,average,poor',
            'grade' => 'required|array',
            'grade.*' => 'nullable|integer|min:0|max:100',
            'observations' => 'required|array',
            'observations.*' => 'nullable|string|max:255',
            'course' => 'required|exists:courses,id',
            'cycle' => 'required|string',
            'cycle_number' => 'required|string',
        ]);

        $courseId = $data['course'];
        $cycle = $data['cycle'];
        $cycleNumber = $data['cycle_number'];

        foreach ($data['conduct'] as $studentId => $status) {
            $grade = $data['grade'][$studentId] ?? null;
            $observations = $data['observations'][$studentId] ?? null;

            Conduct::updateOrCreate(
                ['student_id' => $studentId, 'course_id' => $courseId, 'cycle' => $cycle, 'cycle_number' => $cycleNumber],
                ['conduct' => $status, 'grade' => $grade, 'observations' => $observations]
            );
        }

        return redirect()->back()->with('success', 'Conducta guardada correctamente.');
    }

    
}
