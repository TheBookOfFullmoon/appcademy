<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMajorRequest;
use App\Http\Requests\UpdateMajorRequest;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index(){
        return "hello";
    }

    public function create(){
        return "this is a create page";
    }

    public function store(StoreMajorRequest $request){
        Major::create($request->post());

        return redirect()->route('admin.majors.index')
            ->with('success', 'Successfully created new major');
    }

    public function edit(Major $major){
        return $major;
    }

    public function update(UpdateMajorRequest $request, Major $major){
        $major->update($request->post());

        return redirect()->route('admin.majors.index')
            ->with('success', 'Successfully updated major');
    }

    public function destroy(Major $major){
        $major->delete();

        return redirect()->route('admin.majors.index')
            ->with('success', 'Successfully deleted major');
    }
}
