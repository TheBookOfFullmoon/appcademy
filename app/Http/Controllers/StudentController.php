<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(){
        if (Auth::user()->role == "admin"){
            return "hai admin";
        }

        return "hai student";
    }

    public function create(){
        return "create page";
    }

    public function store(StoreStudentRequest $studentRequest, StoreUserRequest $userRequest){
        $user = User::create([
            'email' => $userRequest->post('email'),
            'password' => 'password',
            'role' => $userRequest->post('role')
        ]);

        Student::create([
            'name' => $studentRequest->post('name'),
            'birthday' => $studentRequest->post('birthday'),
            'birth_place' => $studentRequest->post('birth_place'),
            'address' => $studentRequest->post('address'),
            'gender' => $studentRequest->post('gender'),
            'phone' => $studentRequest->post('phone'),
            'major_id' => $studentRequest->post('major_id'),
            'user_id'=> $user->id
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Successfully created new student');
    }

    public function edit(Student $student){
        return $student;
    }

    public function update(UpdateStudentRequest $studentRequest, Student $student, UpdateUserRequest $userRequest){
        $user = User::find($student->user->id);

        $user->update([
            'email' => $userRequest->post('email'),
            'password' => $userRequest->post('password'),
            'role' => $userRequest->post('role')
        ]);

        $student->update([
            'name' => $studentRequest->post('name'),
            'birthday' => $studentRequest->post('birthday'),
            'birth_place' => $studentRequest->post('birth_place'),
            'address' => $studentRequest->post('address'),
            'gender' => $studentRequest->post('gender'),
            'phone' => $studentRequest->post('phone'),
            'major_id' => $studentRequest->post('major_id'),
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', "Successfully updated student");
    }

    public function destroy(Student $student){
        $student->delete();

        $user = User::find($student->user->id);

        $user->delete();

        return redirect()->route("admin.students.index")
            ->with('success', "Successfully deleted student");
    }
}
