<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(){
        return "schedule page";
    }

    public function create(){
        return "create schedule page";
    }

    public function store(StoreScheduleRequest $request){
        Schedule::create($request->post());

        return redirect()->route("admin.schedules.index")
            ->with('success', "Successfully created a new schedule");
    }

    public function edit(Schedule $schedule){
        return $schedule;
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule){
        $schedule->update($request->post());

        return redirect()->route("admin.schedules.index")
            ->with('success', "Successfully updated schedule");
    }

    public function destroy(Schedule $schedule){
        $schedule->delete();

        return redirect()->route("admin.schedules.index")
            ->with('success', "Successfully deleted schedule");
    }
}
