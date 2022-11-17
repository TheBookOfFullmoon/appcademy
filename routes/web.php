<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

//Auth::routes();


Route::get('login', [\App\Http\Controllers\AuthController::class, 'index'])->name('login');
Route::post('post-login', [\App\Http\Controllers\AuthController::class, 'postLogin'])->name('login.post');
Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::resource('lecturers', \App\Http\Controllers\LecturerController::class)->except(['show', 'create', 'store', 'destroy'])->middleware('lecturer');

Route::resource('students', \App\Http\Controllers\StudentController::class)->except(['show', 'create', 'store', 'destroy'])->middleware('student');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function(){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
   Route::resource('majors', \App\Http\Controllers\MajorController::class)->except(['show']);

   Route::resource('students', \App\Http\Controllers\StudentController::class);

   Route::resource('lecturers', \App\Http\Controllers\LecturerController::class);

   Route::resource('subjects', \App\Http\Controllers\SubjectController::class);

   Route::resource('schedules', \App\Http\Controllers\ScheduleController::class);
});


