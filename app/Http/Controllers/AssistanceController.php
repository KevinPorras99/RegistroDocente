<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Assistance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AssistanceController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::all();

        // Obtener el rango de fechas
        $startDate = $request->has('start_date') ? Carbon::parse($request->start_date) : null;
        $endDate = $request->has('end_date') ? Carbon::parse($request->end_date) : null;
        $dates = ($startDate && $endDate) ? $this->generateDateRange($startDate, $endDate) : [];

        return view('asistencia', compact('courses', 'dates'));
    }

    public function showAssistance(Request $request)
    {
        $courses = Course::all();

        // Aplicar filtros de curso
        $studentsQuery = Student::with(['courses', 'assistances' => function ($query) use ($request) {
            if ($request->has('course') && $request->course != '') {
                $query->where('course_id', $request->course);
            }
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }
        }]);

        if ($request->has('course') && $request->course != '') {
            $studentsQuery->whereHas('courses', function ($query) use ($request) {
                $query->where('courses.id', $request->course);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $studentsQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $students = $studentsQuery->paginate(10);

        // Obtener el rango de fechas
        $startDate = $request->has('start_date') ? Carbon::parse($request->start_date) : null;
        $endDate = $request->has('end_date') ? Carbon::parse($request->end_date) : null;
        $dates = ($startDate && $endDate) ? $this->generateDateRange($startDate, $endDate) : [];

        return view('asistencia', compact('courses', 'students', 'dates'));
    }

    private function generateDateRange(Carbon $startDate, Carbon $endDate)
    {
        $dates = [];
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->copy();
        }
        return $dates;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'attendance' => 'required|array',
            'attendance.*.*' => 'nullable|in:present,late,absent',
            'course' => 'required|exists:courses,id',
        ]);

        $courseId = $data['course'];

        foreach ($data['attendance'] as $studentId => $attendances) {
            $student = Student::find($studentId);

            if ($student && $student->courses->contains($courseId)) {
                foreach ($attendances as $date => $status) {
                    if (!empty($status)) {
                        Assistance::updateOrCreate(
                            ['student_id' => $studentId, 'course_id' => $courseId, 'date' => $date],
                            ['status' => $status]
                        );
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Asistencia guardada correctamente.');
    }
}