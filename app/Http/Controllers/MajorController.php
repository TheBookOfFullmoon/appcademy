<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMajorRequest;
use App\Http\Requests\UpdateMajorRequest;
use App\Models\Major;
use App\Models\User;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index(){
        $majors = Major::paginate(5);
        return view('admin.major.index', compact('majors'));
    }

    public function create(){
        return view('admin.major.create');
    }

    public function store(StoreMajorRequest $request){
        Major::create($request->post());

        return redirect()->route('admin.majors.index')
            ->with('success', 'Successfully created new major');
    }

    public function edit(Major $major){
        return view('admin.major.edit', compact('major'));
    }

    public function update(UpdateMajorRequest $request, Major $major){
        $major->update($request->post());

        return redirect()->route('admin.majors.index')
            ->with('success', 'Successfully updated major');
    }

    public function destroy(Major $major){
        foreach($major->students as $student){
            $user = User::find($student->user_id);
            $user->delete();
        }

        $major->delete();

        return redirect()->route('admin.majors.index')
            ->with('success', 'Successfully deleted major');
    }

    public function search(Request $request){
        $majors = Major::where('name', 'LIKE', '%'.$request->get('keyword').'%')
            ->paginate(5);

        return view('admin.major.index', compact('majors'));
    }
}
