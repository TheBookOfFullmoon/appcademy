<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(){
        if (Auth::user()->role = "admin"){
            return "hai admin";
        }

        return "hai student";
    }

    public function create(){
        return "create page";
    }

    public function store(StoreStudentRequest $request){
        Student::create($request->post());

        return redirect()->route('admin.students.index')
            ->with('success', 'Successfully created new student');
    }

    public function edit(Student $student){
        return $student;
    }

    public function update(UpdateStudentRequest $request, Student $student){
        $student->update($request->post());

        return redirect()->route('admin.students.index')
            ->with('success', "Successfully updated student");
    }

    public function destroy(Student $student){
        $student->delete();

        return redirect()->route("admin.students.index")
            ->with('success', "Successfully deleted student");
    }
}
