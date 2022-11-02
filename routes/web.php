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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('lecturers', \App\Http\Controllers\LecturerController::class)->middleware('lecturer');

Route::resource('students', \App\Http\Controllers\StudentController::class)->middleware('student');

Route::group(['prefix' => 'admin'], function(){
   Route::resource('majors', \App\Http\Controllers\MajorController::class)->middleware('admin');
});


