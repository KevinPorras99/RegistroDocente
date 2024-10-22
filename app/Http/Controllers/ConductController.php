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

        // Aplicar filtros de curso y ciclo
        $studentsQuery = Student::with(['courses', 'conducts' => function ($query) use ($request) {
            if ($request->has('course') && $request->course != '') {
                $query->where('course_id', $request->course);
            }
            if ($request->has('cycle') && $request->cycle != '') {
                $query->where('cycle', $request->cycle);
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

        // Obtener las conductas que necesitan justificación
        $justifications = Conduct::where('course_id', $request->course)
            ->where('cycle', $request->cycle)
            ->with('student') // Asegúrate de cargar la relación con el estudiante
            ->get();

        return view('conducta', compact('courses', 'students', 'justifications'));
    }

    public function storeGrades(Request $request)
    {
        $data = $request->validate([
            'conduct' => 'required|array',
            'conduct.*' => 'nullable|in:good,average,poor',
            'course' => 'required|exists:courses,id',
            'cycle' => 'required|string',
        ]);

        $courseId = $data['course'];
        $cycle = $data['cycle'];

        foreach ($data['conduct'] as $studentId => $status) {
            if (!empty($status)) {
                Conduct::updateOrCreate(
                    ['student_id' => $studentId, 'course_id' => $courseId, 'cycle' => $cycle],
                    ['description' => $status]
                );
            }
        }

        return redirect()->back()->with('success', 'Conducta guardada correctamente.');
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
            $conduct = Conduct::find($id);

            if ($conduct) {
                $justificationData = [
                    'conduct_id' => $conduct->id,
                    'observations' => $justification['observations'] ?? null,
                    'justification_status' => $justification['justification_status'],
                ];

                if (isset($justification['file'])) {
                    $filePath = $justification['file']->store('public/justifications');
                    $justificationData['file_path'] = str_replace('public/', '', $filePath);
                }

                Justification::updateOrCreate(
                    ['conduct_id' => $conduct->id],
                    $justificationData
                );
            }
        }

        return redirect()->back()->with('success', 'Justificaciones guardadas correctamente.');
    }

    public function showJustifications(Request $request)
    {
        $courses = Course::all();

        // Aplicar filtros de curso y ciclo
        $studentsQuery = Student::with(['courses', 'conducts' => function ($query) use ($request) {
            if ($request->has('course') && $request->course != '') {
                $query->where('course_id', $request->course);
            }
            if ($request->has('cycle') && $request->cycle != '') {
                $query->where('cycle', $request->cycle);
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

        // Obtener las conductas que necesitan justificación
        $justifications = Conduct::where('course_id', $request->course)
            ->where('cycle', $request->cycle)
            ->with(['student', 'justifications']) // Asegúrate de cargar la relación con el estudiante y las justificaciones
            ->get();

        return view('conducta', compact('courses', 'students', 'justifications'));
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
}
