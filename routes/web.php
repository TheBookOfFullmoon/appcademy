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
   Route::get('majors/search', [\App\Http\Controllers\MajorController::class, 'search'])->name('majors.search');

   Route::resource('students', \App\Http\Controllers\StudentController::class)->except(['show']);
   Route::get('/students/search', [\App\Http\Controllers\StudentController::class, 'search'])->name('students.search');

   Route::resource('lecturers', \App\Http\Controllers\LecturerController::class)->except(['show']);
   Route::get('/lecturers/search', [\App\Http\Controllers\LecturerController::class, 'search'])->name('lecturers.search');

   Route::resource('subjects', \App\Http\Controllers\SubjectController::class)->except(['show']);
   Route::get('/subjects/search', [\App\Http\Controllers\SubjectController::class, 'search'])->name('subjects.search');
   Route::get('/subjects/{subject}/assigned', [\App\Http\Controllers\SubjectController::class, 'assigned'])->name('subjects.assigned');
   Route::post('subjects/{subject}/assign/{student}', [\App\Http\Controllers\SubjectController::class, 'assign'])->name('subjects.assign');
   Route::get('/subjects/{subject}/unassigned', [\App\Http\Controllers\SubjectController::class, 'unassigned'])->name('subjects.unassigned');
   Route::delete('/subjects/{subject}/unassigned/{student}', [\App\Http\Controllers\SubjectController::class, 'unassign'])->name('subjects.unassign');
});


