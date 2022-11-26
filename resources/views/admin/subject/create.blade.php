@extends('layouts.dash_layout')

@section('page_title', 'Create Subject Data')

@section('content')
    <div class="card shadow mb-3">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Subject Data</p>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('admin.subjects.store')}}" class="needs-validation" novalidate>
                @csrf
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="subjectName"><strong>Subject Name</strong></label>
                            <input type="text" class="form-control" id="name" placeholder="Subject Name" name="name" autocomplete="off"  value="{{old('name')}}" required/>
                            <div class="invalid-feedback">
                                Please provide a subject name.
                            </div>
                        </div>
                        @error('name')
                        <span class="text-danger mt-2">{{$message}}</span>
                        @enderror
                    </div>

                </div>

                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="sks
                        "><strong>SKS</strong></label>
                            <input type="number" class="form-control" id="sks" placeholder="SKS" name="sks" autocomplete="off"  value="{{old('sks')}}" required min="1" max="8"/>
                            <div class="invalid-feedback">
                                Please provide sks.
                            </div>
                        </div>
                        @error('sks')
                        <span class="text-danger mt-2">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="room
                        "><strong>Room</strong></label>
                            <input type="text" class="form-control" id="room" placeholder="Room" name="room" autocomplete="off" required value="{{old('room')}}"/>
                            <div class="invalid-feedback">
                                Please provide a room.
                            </div>
                        </div>
                        @error('room')
                        <span class="text-danger mt-2">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="day
                        "><strong>Day</strong></label>
                            <select class="form-select" aria-label="Default select example" name="day_name" required="true">
                                <option value="" selected>Select Day</option>
                                @foreach($days as $day)
                                    <option value="{{$day}}">{{$day}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a day.
                            </div>
                            @error('day_name')
                            <span class="text-danger mt-2">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="lecturer
                        "><strong>Lecturer</strong></label>
                            <select class="form-select" aria-label="Default select example" name="lecturer_id" required="true">
                                <option value="" selected>Select Lecturer</option>
                                @foreach($lecturers as $lecturer)
                                    <option value="{{$lecturer->id}}">{{$lecturer->name}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a lecturer.
                            </div>
                            @error('lecturer_id')
                            <span class="text-danger mt-2">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{--                <div class="row">--}}
                {{--                    <div class="col">--}}
                {{--                        <div class="mb-3"><label class="form-label" for="email"><strong>Email Address</strong></label><input type="email" class="form-control" id="email" placeholder="user@example.com" name="email" /></div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                <div class="mb-3 text-end">
                    <a class="btn btn-warning" href="{{route('admin.subjects.index')}}">Back</a>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('assets/js/validation.js')}}"></script>
@endsection
