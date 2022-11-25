<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Major;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(){
        if (Auth::user()->role == "admin"){
            $students = Student::with('user', 'major')->paginate(5);

            return view('admin.student.index', compact('students'));
        }

        return "hai student";
    }

    public function create(){
        $majors = Major::all();

        return view('admin.student.create', compact('majors'));
    }

    public function store(StoreStudentRequest $studentRequest, StoreUserRequest $userRequest){
        $user = User::create([
            'email' => $userRequest->post('email'),
            'password' => 'password',
            'role' => 'student'
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
        $majors = Major::all();

        $birthday = Carbon::parse($student->birthday)->format('Y-m-d');
//        dd($birthday);

        return view('admin.student.edit', compact('student', 'majors', 'birthday'));

    }

    public function update(UpdateStudentRequest $studentRequest, Student $student, UpdateUserRequest $userRequest){
        $user = User::find($student->user->id);

        $user->update([
            'email' => $userRequest->post('email'),
            'password' => $user->password,
            'role' => 'student'
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

        if (Auth::user()->role == 'admin'){
            return redirect()->route('admin.students.index')
                ->with('success', "Successfully updated student");
        }

        return redirect()->route('students.index')
            ->with('success', "Successfully updated student");
    }

    public function destroy(Student $student){
        $student->delete();

        $user = User::find($student->user->id);

        $user->delete();

        return redirect()->route("admin.students.index")
            ->with('success', "Successfully deleted student");
    }

    public function search(Request $request){
        $students = Student::with('user', 'major')
            ->search($request->get('keyword'))->paginate(5);

        return view('admin.student.index', compact('students'));
    }
}
