<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLecturerRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateLecturerRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerController extends Controller
{
    public function index(){
        if (Auth::user()->role == 'admin'){
            return "hai admin";
        }

        return "hai lecturer";
    }

    public function create(){
        return "create page";
    }

    public function store(StoreLecturerRequest $lecturerRequest, StoreUserRequest $userRequest){
        $user = User::create([
            'email' => $userRequest->post('email'),
            'password' => 'password',
            'role' => $userRequest->post('role')
        ]);

        Lecturer::create([
            'name' => $lecturerRequest->post('name'),
            'birthday' => $lecturerRequest->post('birthday'),
            'birth_place' => $lecturerRequest->post('birth_place'),
            'address' => $lecturerRequest->post('address'),
            'gender' => $lecturerRequest->post('gender'),
            'phone' => $lecturerRequest->post('phone'),
            'user_id'=> $user->id
        ]);

        return redirect()->route('admin.lecturers.index')
            ->with('success', "Successfully created new lecturer");
    }

    public function edit(Lecturer $lecturer){
        return $lecturer;
    }

    public function update(UpdateLecturerRequest $lecturerRequest, Lecturer $lecturer, UpdateUserRequest $userRequest){
        $user = User::find($lecturer->user->id);

        $user->update([
            'email' => $userRequest->post('email'),
            'password' => $userRequest->post('password'),
            'role' => $userRequest->post('role')
        ]);

        $lecturer->update([
            'name' => $lecturerRequest->post('name'),
            'birthday' => $lecturerRequest->post('birthday'),
            'birth_place' => $lecturerRequest->post('birth_place'),
            'address' => $lecturerRequest->post('address'),
            'gender' => $lecturerRequest->post('gender'),
            'phone' => $lecturerRequest->post('phone'),
        ]);

        return redirect()->route('admin.lecturers.index')
            ->with('success', "Successfully updated lecturer");
    }

    public function destroy(Lecturer $lecturer){
        $lecturer->delete();

        $user = User::find($lecturer->user->id);

        $user->delete();

        return redirect()->route("admin.lecturers.index")
            ->with('success', "Successfully deleted lecturer");
    }
}
