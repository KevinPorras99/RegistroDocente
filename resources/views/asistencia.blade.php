@extends('sideynavbar')

@section('title', 'Asistencia')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
    <style>
        .attendance-marked {
            position: relative;
        }
        .attendance-marked.present::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 10px;
            height: 10px;
            background-color: green;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }
        .attendance-marked.late::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 10px;
            height: 10px;
            background-color: yellow;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }
        .attendance-marked.absent::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 10px;
            height: 10px;
            background-color: red;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }
        .calendar-day-marked {
            position: relative;
        }
        .calendar-day-marked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            border: 2px solid blue;
            border-radius: 50%;
            background-color: transparent;
            transform: translate(-50%, -50%);
        }
    </style>
@endsection

@section('content')
    <div class="content">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0"><i class="fas fa-calendar-check"></i> Asistencia</h1>
            <form id="filterForm" action="{{ route('asistencia.show') }}" method="GET" class="d-inline-block">
                <h2><i class="fas fa-filter"></i> Filtros</h2>
                <select id="courseSelect" name="course" class="form-control d-inline-block" style="width: 300px;">
                    <option value="">Seleccione un curso</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} - {{ $course->grade }} - {{ $course->institution }} - {{ $course->classroom }}
                        </option>
                    @endforeach
                </select>
                <input type="date" name="start_date" class="form-control d-inline-block ml-2" style="width: 150px;"
                    value="{{ request('start_date') }}">
                <input type="date" name="end_date" class="form-control d-inline-block ml-2" style="width: 150px;"
                    value="{{ request('end_date') }}">
                <button type="submit" class="btn btn-primary ml-2 mt-md-0">Filtrar</button>
            </form>
        </div>

        <!-- Barra de búsqueda y botón para agregar asistencia -->
        <div class="d-flex flex-wrap mb-3">
            <form action="{{ route('asistencia.show') }}" method="GET" class="mr-2 flex-grow-1 d-flex">
                <input type="hidden" name="course" value="{{ request('course') }}">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <input type="text" name="search" placeholder="Buscar estudiante..." class="form-control"
                    style="width: 60%;" {{ (!request('start_date') || !request('end_date')) ? 'disabled' : '' }}>
                <button type="submit" class="btn btn-primary ml-2 mt-md-0" {{ (!request('start_date') || !request('end_date')) ? 'disabled' : '' }}>Buscar</button>
                <a href="{{ route('asistencia') }}" class="btn btn-secondary ml-2 mt-md-0">Borrar Filtros</a>
            </form>
        </div>

        <!-- Mostrar mensaje si no se han seleccionado todos los filtros -->
        @if (!request('course') || !request('start_date') || !request('end_date'))
            <div class="alert alert-warning">
                Por favor, seleccione un curso y un rango de fechas para ver la lista de estudiantes.
            </div>
        @else
        @if (session('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
        @endif

            <!-- Botón para escanear QR -->
            <button class="btn btn-primary mb-3" onclick="openQrScanner()">Escanear QR</button>

            <!-- Contenedor para el escáner QR -->
            <div id="qrScanner" style="display: none;">
                <video id="qrVideo" width="300" height="200"></video>
                <button class="btn btn-secondary" onclick="closeQrScanner()">Cerrar</button>
                <button class="btn btn-primary" onclick="captureImage()">Tomar Foto</button>
            </div>

            <!-- Lista de estudiantes en formato de calendario -->
            <form action="{{ route('asistencia.store') }}" method="POST">
                @csrf
                <input type="hidden" name="course" value="{{ request('course') }}">
                <div class="container">
                    <h1>Lista de Estudiantes</h1>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre del Estudiante</th>
                                    @foreach ($dates as $date)
                                        <th>{{ $date->format('d-m-Y') }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    @foreach ($dates as $date)
                                        @php
                                            $attendance = $student->assistances->firstWhere('date', $date->format('Y-m-d'));
                                            $markedClass = '';
                                            if ($attendance) {
                                                if ($attendance->status == 'present') {
                                                    $markedClass = 'attendance-marked present';
                                                } elseif ($attendance->status == 'late') {
                                                    $markedClass = 'attendance-marked late';
                                                } elseif ($attendance->status == 'absent') {
                                                    $markedClass = 'attendance-marked absent';
                                                }
                                            }
                                        @endphp
                                        <td id="attendance-{{ $student->id }}-{{ $date->format('Y-m-d') }}" class="{{ $markedClass }}">
                                            <select name="attendance[{{ $student->id }}][{{ $date->format('Y-m-d') }}]" class="form-control">
                                                <option value="">Sin asignar</option>
                                                <option value="present" {{ $attendance && $attendance->status == 'present' ? 'selected' : '' }}>Presente</option>
                                                <option value="late" {{ $attendance && $attendance->status == 'late' ? 'selected' : '' }}>Tardía</option>
                                                <option value="absent" {{ $attendance && $attendance->status == 'absent' ? 'selected' : '' }}>Ausente</option>
                                            </select>
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Botón para guardar asistencia -->
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Guardar Asistencia</button>
                </div>
            </form>

            <!-- Lista de justificaciones -->
            <div class="container mt-5">
                <h2>Justificaciones de Ausencias y Tardías</h2>
                <form action="{{ route('justifications.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre del Estudiante</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Justificante</th>
                                    <th>Observaciones</th>
                                    <th>Justificación</th>
                                    <th>Porcentaje de Asistencia total del estudiante</th> <!-- Nueva columna -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($justifications as $justification)
                                    <tr>
                                        <td>{{ $justification->student->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($justification->date)->format('d-m-Y') }}</td>
                                        <td>
                                            @php
                                                $statusTranslations = [
                                                    'late' => 'Tardía',
                                                    'absent' => 'Ausente'
                                                ];
                                            @endphp
                                            {{ $statusTranslations[$justification->status] ?? ucfirst($justification->status) }}
                                        </td>
                                        <td>
                                            @php
                                                $existingJustification = $justification->justifications->first();
                                            @endphp
                                            @if ($existingJustification && $existingJustification->file_path)
                                                <a href="{{ asset('storage/' . $existingJustification->file_path) }}" target="_blank">Ver archivo</a>
                                            @endif
                                            <input type="file" name="justifications[{{ $justification->id }}][file]" class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" name="justifications[{{ $justification->id }}][observations]" class="form-control" value="{{ $existingJustification ? $existingJustification->observations : '' }}">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <select name="justifications[{{ $justification->id }}][justification_status]" class="form-control mr-2">
                                                    <option value="not_justified" {{ $existingJustification && $existingJustification->justification_status == 'not_justified' ? 'selected' : '' }}>Sin justificar</option>
                                                    <option value="justified" {{ $existingJustification && $existingJustification->justification_status == 'justified' ? 'selected' : '' }}>Justificado</option>
                                                </select>
                                                @if ($existingJustification && $existingJustification->justification_status == 'justified')
                                                    <i class="fas fa-check-circle text-success"></i>
                                                @else
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $course = $justification->course;
                                                if ($course) {
                                                    $totalAssistances = $justification->student->assistances->where('course_id', $course->id)->count();
                                                    $absences = $justification->student->assistances->where('course_id', $course->id)->where('status', 'absent')->count();
                                                    $lates = $justification->student->assistances->where('course_id', $course->id)->where('status', 'late')->count();

                                                    // Ajustar los valores si están justificados
                                                    foreach ($justification->student->assistances->where('course_id', $course->id) as $assistance) {
                                                        $justification = $assistance->justifications->first();
                                                        if ($justification && $justification->justification_status == 'justified') {
                                                            if ($assistance->status == 'absent') {
                                                                $absences--;
                                                            } elseif ($assistance->status == 'late') {
                                                                $lates--;
                                                            }
                                                        }
                                                    }

                                                    $attendancePercentage = $course->attendance_percentage;
                                                    $deductionPerAbsence = $attendancePercentage / $totalAssistances;
                                                    $deductionPerLate = $deductionPerAbsence / 2;

                                                    $finalAttendancePercentage = $attendancePercentage - ($absences * $deductionPerAbsence) - ($lates * $deductionPerLate);
                                                } else {
                                                    $finalAttendancePercentage = 'N/A';
                                                }
                                            @endphp
                                            {{ $finalAttendancePercentage }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Guardar Justificaciones</button>
                    </div>
                </form>
            </div>

            <!-- Enlaces de paginación -->
            <div class="d-flex justify-content-center">
                {{ $students->links('pagination::bootstrap-4') }}
            </div>
            @endif
            </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsqr/1.4.0/jsQR.min.js"></script>
    <script>
        let video = document.getElementById('qrVideo');
        let canvasElement = document.createElement('canvas');
        let canvas = canvasElement.getContext('2d');
        let scanning = false;

        function openQrScanner() {
            document.getElementById('qrScanner').style.display = 'block';
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } }).then(function(stream) {
                video.srcObject = stream;
                video.setAttribute('playsinline', true); // required to tell iOS safari we don't want fullscreen
                video.play();
                scanning = true;
                tick();
            });
        }

        function closeQrScanner() {
            scanning = false;
            video.srcObject.getTracks().forEach(track => track.stop());
            document.getElementById('qrScanner').style.display = 'none';
        }

        function tick() {
            canvasElement.height = video.videoHeight;
            canvasElement.width = video.videoWidth;
            canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
            scanning && requestAnimationFrame(tick);
        }

        function captureImage() {
            let imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
            let code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: 'dontInvert',
            });
            if (code) {
                scanning = false;
                video.srcObject.getTracks().forEach(track => track.stop());
                document.getElementById('qrScanner').style.display = 'none';
                markAttendance(code.data);
            } else {
                alert('No se pudo detectar un código QR. Inténtalo de nuevo.');
            }
        }

        function markAttendance(studentId) {
            const courseId = document.getElementById('course').value;
            const date = new Date().toISOString().split('T')[0]; // Obtener la fecha actual en formato YYYY-MM-DD
            fetch('{{ route('assistance.markAttendance') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ student_id: studentId, course_id: courseId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Asistencia marcada correctamente.');
                    // Actualizar la celda correspondiente en la tabla de calendario
                    const cell = document.getElementById(`attendance-${studentId}-${date}`);
                    if (cell) {
                        cell.classList.remove('attendance-marked', 'late', 'absent');
                        cell.classList.add('attendance-marked', 'present');
                        const select = cell.querySelector('select');
                        if (select) {
                            select.value = 'present';
                        }
                    }
                } else {
                    alert('Error al marcar la asistencia.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al marcar la asistencia.');
            });
        }

        $(document).ready(function() {
            setTimeout(function() {
                $('#success-message').fadeOut('slow');
            }, 3000); // 3 segundos
        });
    </script>
@endsection