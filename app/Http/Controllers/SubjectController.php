<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index(){
        return "subject page";
    }

    public function create(){
        return "subject create page";
    }

    public function store(StoreSubjectRequest $request){
        Subject::create($request->post());

        return redirect()->route('admin.subjects.index')
            ->with('success', "Successfully created a new subject");
    }

    public function edit(Subject $subject){
        return $subject;
    }

    public function update(UpdateSubjectRequest $request, Subject $subject){
        $subject->update($request->post());

        return redirect()->route("admin.subjects.index")
            ->with('success', "Successfully updated subject");
    }

    public function destroy(Subject $subject){
        $subject->delete();

        return redirect()->route("admin.subjects.index")
            ->with('success', "Successfully deleted subject");
    }
}
