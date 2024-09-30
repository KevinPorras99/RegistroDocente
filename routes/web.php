<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DailyWorkController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamGradeController;
use App\Http\Controllers\GradeController;

// Vista por defecto redirigida a login
Route::get('/', function () {
    return redirect()->route('login.index');
})->middleware('guest');
//estudiantes
Route::resource('students', StudentController::class)->middleware('auth');
Route::get('/estudiantes', [StudentController::class, 'index'])->name('estudiantes');
//cursos
Route::resource('courses', CourseController::class)->middleware('auth');
Route::get('/cursos', [CourseController::class, 'index'])->name('cursos');
Route::post('/cursos/assign-students', [CourseController::class, 'assignStudents'])->name('courses.assignStudents');
Route::get('/cursos/{courseId}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/cursos/{id}/detalles', [CourseController::class, 'showDailyTask'])->name('courses.showDailyTask');

Route::get('/cursos/{courseId}/student/{studentId}/courseworks', [DailyWorkController::class, 'getCourseWorks']);
//cotidiano
Route::resource('tasks', TaskController::class)->middleware('auth');
Route::get('/tareasyasignaciones', [TaskController::class, 'index'])->name('tareasyasignaciones');
Route::get('tasks/{courseId}/{cycle}/add-grades', [TaskController::class, 'showAddGradesForm'])->name('add-grades');
Route::post('tasks/{courseId}/store-grades', [TaskController::class, 'storeGrades'])->name('storeGrades');

Route::resource('dailyWorks', DailyWorkController::class)->middleware('auth');
Route::get('/trabajocotidiano', [DailyWorkController::class, 'index'])->name('trabajocotidiano');
//examenes
Route::resource('exams', ExamController::class)->middleware('auth');
Route::get('/examenes', [ExamController::class, 'index'])->name('examenes');
Route::get('exams/{courseId}/{cycle}/add-grades', [ExamGradeController::class, 'showAddGradesForm'])->name('add-grades-exams');
Route::post('exams/{courseId}/store-grades', [ExamGradeController::class, 'storeGrades'])->name('exams.storeGrades');

//login
Route::get('/login', [SessionController::class, 'create'])->middleware('guest')->name('login.index');
Route::post('/login', [SessionController::class, 'store'])->name('login.store');
Route::get('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('login.destroy');
Route::get('/forgot-password', [SessionController::class, 'forgotPasswordView'])->name('password.request');
Route::post('/forgot-password', [SessionController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password/{token}', [SessionController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [SessionController::class, 'reset'])->name('password.update');
Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register.index');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/home', [SessionController::class, 'home'])->middleware('auth')->name('home');

Route::post('/profile/upload', [SessionController::class, 'uploadProfileImage'])->name('profile.upload')->middleware('auth');
Route::delete('/profile/delete', [SessionController::class, 'deleteProfileImage'])->name('profile.delete')->middleware('auth');

Route::get('/estudiantes/download-template', [StudentController::class, 'downloadTemplate'])->name('students.downloadTemplate');
Route::post('/estudiantes/upload-excel', [StudentController::class, 'uploadExcel'])->name('students.uploadExcel');

Route::get('courses/{course}/cycles/{cycle}/daily-works', [DailyWorkController::class, 'getDailyWorks'])->name('dailyWorks.getDailyWorks');
Route::post('grades/store', [GradeController::class, 'store'])->name('grades.store');

Route::get('courses/{course}/cycles/{cycle}/grades', [GradeController::class, 'showAddGradesForm'])->name('grades.showAddForm');
Route::post('courses/{course}/cycles/{cycle}/grades', [GradeController::class, 'store'])->name('grades.store');

// Rutas duplicadas comentadas(se necesitan)
Route::get('/cursos/{course}/ciclos/{cycle}/calificaciones', [GradeController::class, 'showAddGradesForm'])->name('grades.showAddForm');
Route::post('/calificaciones', [GradeController::class, 'store'])->name('grades.store');

Route::get('/cursos/{course}/ciclos/{cycle}/calificaciones', [GradeController::class, 'showAddGradesForm'])->name('grades.showAddForm')->middleware('auth');
Route::post('/courses/{course}/dailyWorks', [DailyWorkController::class, 'storeDailyWork'])->name('courses.dailyWorks.store');
Route::get('/courses/{courseId}/allowedPercentage', [DailyWorkController::class, 'getAllowedPercentage']);

Route::get('/dailyWorks/{courseId}/{cycle}', [DailyWorkController::class, 'getDailyWorksByCourseAndCycle']);
Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
Route::get('/students/{student}/dailyWorks', [StudentController::class, 'getDailyWorks']);

Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');

Route::middleware(['auth'])->group(function () {
    // Rutas de tareas
    Route::get('/courses/{courseId}/grades/{cycle}', [DailyWorkController::class, 'showAddGradesForm'])->name('dailyWorks.showAddGradesForm');
    Route::post('/courses/{courseId}/grades', [DailyWorkController::class, 'storeGrades'])->name('dailyWorks.storeGrades');
    Route::post('/grades/store', [DailyWorkController::class, 'storeGrades'])->name('grades.store');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('tasks', TaskController::class);
    Route::get('tasks/{courseId}/{cycle}/add-grades', [TaskController::class, 'showAddGradesForm'])->name('add-grades-tasks');
    Route::post('tasks/{courseId}/store-grades', [TaskController::class, 'storeGrades'])->name('tasks.storeGrades');
});
