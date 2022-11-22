@extends('layouts.dash_layout')

@section('page_title', 'Edit Student Data')

@section('alert')
    @error('name')
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{$message}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @enderror
@endsection

@section('content')
    <div class="card shadow mb-3">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Student Data</p>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('admin.students.update', $student->id)}}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="studentName"><strong>Name</strong></label><input type="text" class="form-control" id="name" placeholder="Name" name="name" autocomplete="off"  value="{{old('name', $student->name)}}" required/></div>
                        @error('name')
                        <span class="text-danger mt-2">{{$message}}</span>
                        @enderror
                    </div>

                </div>

                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="birthPlace
                        "><strong>Birth Place</strong></label><input type="text" class="form-control" id="birthPlace" placeholder="Birth Place" name="birth_place" autocomplete="off"  value="{{old('birth_place', $student->birth_place)}}" required/></div>
                        @error('birth_place')
                        <span class="text-danger mt-2">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="birthday
                        "><strong>Birthday</strong></label><input type="date" class="form-control" id="birthday" name="birthday" autocomplete="off" required value="{{old('birthday', $birthday)}}"/></div>
                        @error('birthday')
                        <span class="text-danger mt-2">{{$message}}</span>
                        @enderror
                    </div>

                </div>
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="address
                        "><strong>Address</strong></label><input type="text" class="form-control" id="address" placeholder="Address" name="address" autocomplete="off" required value="{{old('address', $student->address)}}"/></div>
                        @error('address')
                        <span class="text-danger mt-2">{{$message}}</span>
                        @enderror
                    </div>

                </div>
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="Gender
                        "><strong>Gender</strong></label>
                            <select class="form-select" aria-label="Default select example" name="gender" required="true">
                                @if($student->gender == 'Male')
                                    <option value="Female" selected>Female</option>
                                @else
                                    <option value="Male" selected>Male</option>
                                @endif
                            </select>
                            @error('gender')
                            <span class="text-danger mt-2">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="phone
                        "><strong>Phone</strong></label><input type="text" class="form-control" id="phone" placeholder="Phone" name="phone" autocomplete="off" required value="{{old('phone', $student->phone)}}"/></div>
                        @error('phone')
                        <span class="text-danger mt-2">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="email
                        "><strong>Email</strong></label><input type="email" class="form-control" id="email" placeholder="Email" name="email" autocomplete="off" required value="{{old('email', $student->user->email)}}"/></div>
                        @error('email')
                        <span class="text-danger mt-2">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="Gender
                        "><strong>Major</strong></label>
                            <select class="form-select" aria-label="Default select example" name="major_id" required="true">
                                <option value="" selected>Select Major</option>
                                @foreach($majors as $major)
                                    @if($major->id == $student->major->id)
                                        <option value="{{$major->id}}" selected>{{$major->name}}</option>
                                    @else
                                        <option value="{{$major->id}}">{{$major->name}}</option>
                                    @endif

                                @endforeach
                            </select>
                        </div>
                        @error('major_id')
                        <span class="text-danger mt-2">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                {{--                <div class="row">--}}
                {{--                    <div class="col">--}}
                {{--                        <div class="mb-3"><label class="form-label" for="email"><strong>Email Address</strong></label><input type="email" class="form-control" id="email" placeholder="user@example.com" name="email" /></div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                <div class="mb-3 text-end"><button class="btn btn-primary" type="submit">Save</button></div>
            </form>
        </div>
    </div>
@endsection