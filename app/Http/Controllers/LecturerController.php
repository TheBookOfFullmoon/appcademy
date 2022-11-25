<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLecturerRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateLecturerRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Lecturer;
use App\Models\Major;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LecturerController extends Controller
{
    public function index(){
        if (Auth::user()->role == 'admin'){
            $lecturers = Lecturer::with('user')->paginate(5);

            return view('admin.lecturer.index', compact('lecturers'));
        }

        return "hai lecturer";
    }

    public function create(){
        return view('admin.lecturer.create');
    }

    public function store(StoreLecturerRequest $lecturerRequest, StoreUserRequest $userRequest){
        $user = User::create([
            'email' => $userRequest->post('email'),
            'password' => 'password',
            'role' => 'lecturer'
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
        $birthday = Carbon::parse($lecturer->birthday)->format('Y-m-d');
//        dd($birthday);

        return view('admin.lecturer.edit', compact('lecturer', 'birthday'));
    }

    public function update(UpdateLecturerRequest $lecturerRequest, Lecturer $lecturer, UpdateUserRequest $userRequest){
        $user = User::find($lecturer->user->id);

        $user->update([
            'email' => $userRequest->post('email'),
            'password' => $user->password,
            'role' => 'lecturer'
        ]);

        $lecturer->update([
            'name' => $lecturerRequest->post('name'),
            'birthday' => $lecturerRequest->post('birthday'),
            'birth_place' => $lecturerRequest->post('birth_place'),
            'address' => $lecturerRequest->post('address'),
            'gender' => $lecturerRequest->post('gender'),
            'phone' => $lecturerRequest->post('phone'),
        ]);

        if (Auth::user()->role == 'admin'){
            return redirect()->route('admin.lecturers.index')
                ->with('success', "Successfully updated lecturer");
        }

        return redirect()->route('lecturers.index')
            ->with('success', "Successfully updated lecturer");
    }

    public function destroy(Lecturer $lecturer){
        $user = User::find($lecturer->user->id);
        $user->delete();

        $lecturer->delete();

        return redirect()->route("admin.lecturers.index")
            ->with('success', "Successfully deleted lecturer");
    }

    public function search(Request $request){
        $lecturers = Lecturer::with('user')
            ->where('name', 'LIKE', '%'.$request->get('keyword').'%')
            ->orWhere('birth_place', 'LIKE', '%'.$request->get('keyword').'%')
            ->orWhere('birthday', 'LIKE', '%'.$request->get('keyword').'%')
            ->orWhere('address', 'LIKE', '%'.$request->get('keyword').'%')
            ->orWhere('gender', 'LIKE', '%'.$request->get('keyword').'%')
            ->orWhere('phone', 'LIKE', '%'.$request->get('keyword').'%')
            ->orWhereHas('user', function($q) use($request){
                return $q->where('email', 'LIKE', '%'.$request->get('keyword'));
            })
            ->paginate(5);

        return view('admin.lecturer.index', compact('lecturers'));
    }
}
