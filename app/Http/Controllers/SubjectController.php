<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Lecturer;
use App\Models\Schedule;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(){
        $subjects = Subject::with('lecturer', 'schedule')
            ->paginate(5);

        return view('admin.subject.index', compact('subjects'));
    }

    public function create(){
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $lecturers = Lecturer::all();

        return view('admin.subject.create', compact('days', 'lecturers'));
    }

    public function store(StoreSubjectRequest $subjectRequest, StoreScheduleRequest $scheduleRequest){
        $subject = Subject::create([
            'name' => $subjectRequest->post('name'),
            'sks' => $subjectRequest->post('sks'),
            'lecturer_id' => $subjectRequest->post('lecturer_id')
        ]);

        Schedule::create([
            'day_name' => $scheduleRequest->post('day_name'),
            'room' => $scheduleRequest->post('room'),
            'subject_id' => $subject->id
        ]);

        return redirect()->route('admin.subjects.index')
            ->with('success', "Successfully created a new subject");
    }

    public function edit(Subject $subject){
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $lecturers = Lecturer::all();

        return view('admin.subject.edit', compact('days', 'lecturers', 'subject'));
    }

    public function update(UpdateSubjectRequest $subjectRequest, Subject $subject, UpdateScheduleRequest $scheduleRequest){
        $subject->update([
            'name' => $subjectRequest->name,
            'sks' => $subjectRequest->sks,
            'lecturer_id' => $subjectRequest->lecturer_id
        ]);

        $schedule = Schedule::where('subject_id', '=', $subject->id);

        $schedule->update([
            'day_name' => $scheduleRequest->day_name,
            'room' => $scheduleRequest->room
        ]);

        return redirect()->route("admin.subjects.index")
            ->with('success', "Successfully updated subject");
    }

    public function destroy(Subject $subject){
        $subject->delete();

        return redirect()->route("admin.subjects.index")
            ->with('success', "Successfully deleted subject");
    }

    public function search(Request $request){
        $subjects = Subject::with('lecturer', 'schedule')
            ->search($request->get('keyword'))
            ->paginate(5);

        return view('admin.subject.index', compact('subjects'));
    }
}
