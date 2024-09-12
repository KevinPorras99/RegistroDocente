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

// Vista por defecto redirigida a login
Route::get('/', function () {
    return redirect()->route('login.index');
})->middleware('guest');

Route::resource('students', StudentController::class)->middleware('auth');
Route::get('/estudiantes', [StudentController::class, 'index'])->name('estudiantes');


Route::resource('courses', CourseController::class)->middleware('auth');
Route::get('/cursos', [CourseController::class, 'index'])->name('cursos');
Route::post('/cursos/assign-students', [CourseController::class, 'assignStudents'])->name('courses.assignStudents');
Route::get('/cursos/{courseId}', [CourseController::class, 'show'])->name('courses.show');

Route::resource('tasks', TaskController::class)->middleware('auth');
Route::get('/tareasyasignaciones', [TaskController::class, 'index'])->name('tareasyasignaciones');

Route::resource('dailyWorks', DailyWorkController::class)->middleware('auth');
Route::get('/trabajocotidiano', [DailyWorkController::class, 'index'])->name('trabajocotidiano');

Route::resource('exams', ExamController::class)->middleware('auth');
Route::get('/examenes', [ExamController::class, 'index'])->name('examenes');

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
// Página de inicio (home) después de autenticarse
//Route::get('/home', function () {
//    return view('home');
//})->middleware('auth')->name('home');
