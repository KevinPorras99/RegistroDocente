<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Assistance;
use App\Models\Justification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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
        $startDate = $request->has('start_date') ? \Carbon\Carbon::parse($request->start_date) : null;
        $endDate = $request->has('end_date') ? \Carbon\Carbon::parse($request->end_date) : null;
        $dates = ($startDate && $endDate) ? $this->generateDateRange($startDate, $endDate) : [];

        // Obtener las fechas con asistencia
        $attendanceDates = Assistance::where('course_id', $request->course)
            ->whereBetween('date', [$startDate, $endDate])
            ->pluck('date')
            ->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        // Obtener las asistencias que necesitan justificación
        $justifications = Assistance::whereIn('status', ['late', 'absent'])
            ->where('course_id', $request->course)
            ->with('student') // Asegúrate de cargar la relación con el estudiante
            ->get();

        return view('asistencia', compact('courses', 'students', 'dates', 'attendanceDates', 'justifications'));
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

 
  
    public function storeJustifications(Request $request)
    {
        $data = $request->validate([
            'justifications' => 'required|array',
            'justifications.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'justifications.*.observations' => 'nullable|string|max:255',
            'justifications.*.justification_status' => 'required|string|in:justified,not_justified',
        ]);

        foreach ($data['justifications'] as $id => $justification) {
            $assistance = Assistance::find($id);

            if ($assistance) {
                $justificationData = [
                    'assistance_id' => $assistance->id,
                    'observations' => $justification['observations'] ?? null,
                    'justification_status' => $justification['justification_status'],
                ];

                if (isset($justification['file'])) {
                    $filePath = $justification['file']->store('public/justifications');
                    $justificationData['file_path'] = str_replace('public/', '', $filePath);
                }

                Justification::updateOrCreate(
                    ['assistance_id' => $assistance->id],
                    $justificationData
                );
            }
        }

        // Calcular el porcentaje de asistencia para cada estudiante
        $students = Student::with(['assistances.course'])->get();

        foreach ($students as $student) {
            foreach ($student->courses as $course) {
                $totalAssistances = $student->assistances->where('course_id', $course->id)->count();
                if ($totalAssistances == 0) {
                    continue; // Evitar división por cero
                }
                $absences = $student->assistances->where('course_id', $course->id)->where('status', 'absent')->count();
                $lates = $student->assistances->where('course_id', $course->id)->where('status', 'late')->count();

                $attendancePercentage = $course->attendance_percentage;
                $deductionPerAbsence = $attendancePercentage / $totalAssistances;
                $deductionPerLate = $deductionPerAbsence / 2;

                $finalAttendancePercentage = $attendancePercentage - ($absences * $deductionPerAbsence) - ($lates * $deductionPerLate);

                $student->courses->find($course->id)->final_attendance_percentage = $finalAttendancePercentage;
            }
        }

        return redirect()->back()->with('success', 'Justificaciones guardadas correctamente.');
    }

    public function showJustifications(Request $request)
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
        $startDate = $request->has('start_date') ? \Carbon\Carbon::parse($request->start_date) : null;
        $endDate = $request->has('end_date') ? \Carbon\Carbon::parse($request->end_date) : null;
        $dates = ($startDate && $endDate) ? $this->generateDateRange($startDate, $endDate) : [];

        // Obtener las fechas con asistencia
        $attendanceDates = Assistance::where('course_id', $request->course)
            ->whereBetween('date', [$startDate, $endDate])
            ->pluck('date')
            ->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        // Obtener las asistencias que necesitan justificación
        $justifications = Assistance::whereIn('status', ['late', 'absent'])
            ->where('course_id', $request->course)
            ->with(['student', 'justifications']) // Asegúrate de cargar la relación con el estudiante y las justificaciones
            ->get();

        return view('asistencia', compact('courses', 'students', 'dates', 'attendanceDates', 'justifications'));
    }

    public function deleteJustificationFile($id)
    {
        $justification = Justification::findOrFail($id);

        // Eliminar el archivo del almacenamiento
        if ($justification->file_path) {
            Storage::delete('public/' . $justification->file_path);
            $justification->file_path = null;
            $justification->save();
        }

        return redirect()->back()->with('success', 'Archivo de justificación eliminado correctamente.');
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $studentId = $request->student_id;
        $courseId = $request->course_id;
        $date = Carbon::now()->format('Y-m-d');

        $assistance = Assistance::updateOrCreate(
            ['student_id' => $studentId, 'course_id' => $courseId, 'date' => $date],
            ['status' => 'present']
        );

        return response()->json(['success' => true]);
    }
    
}