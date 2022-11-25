@extends('layouts.dash_layout')

@section('page_title', 'Create Student Data')

@section('content')
    <div class="card shadow mb-3">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Student Data</p>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('admin.students.store')}}" class="needs-validation" novalidate>
                @csrf
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="studentName"><strong>Name</strong></label>
                            <input type="text" class="form-control" id="name" placeholder="Name" name="name" autocomplete="off"  value="{{old('name')}}" required/>
                            <div class="invalid-feedback">
                                Please provide a name.
                            </div>
                        </div>
                        @error('name')
                        <span class="text-danger mt-2">{{$message}}</span>
                        @enderror
                    </div>

                </div>

                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="birthPlace
                        "><strong>Birth Place</strong></label>
                            <input type="text" class="form-control" id="birthPlace" placeholder="Birth Place" name="birth_place" autocomplete="off"  value="{{old('birth_place')}}" required/>
                            <div class="invalid-feedback">
                                Please provide a birthplace.
                            </div>
                        </div>
                        @error('birth_place')
                        <span class="text-danger mt-2">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="birthday
                        "><strong>Birthday</strong></label>
                            <input type="date" class="form-control" id="birthday" placeholder="dd/mm/yyy" name="birthday" autocomplete="off" required value="{{old('birthday')}}"/>
                            <div class="invalid-feedback">
                                Please provide a birthday.
                            </div>
                        </div>
                        @error('birthday')
                        <span class="text-danger mt-2">{{$message}}</span>
                        @enderror
                    </div>

                </div>
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="address
                        "><strong>Address</strong></label>
                            <input type="text" class="form-control" id="address" placeholder="Address" name="address" autocomplete="off" required value="{{old('address')}}"/>
                            <div class="invalid-feedback">
                                Please provide an address.
                            </div>
                        </div>
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
                                <option value="" selected>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a gender.
                            </div>
                            @error('gender')
                            <span class="text-danger mt-2">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="phone
                        "><strong>Phone</strong></label>
                            <input type="text" class="form-control" id="phone" placeholder="Phone" name="phone" autocomplete="off" required value="{{old('phone')}}"/>
                            <div class="invalid-feedback">
                                Please provide a phone number.
                            </div>
                        </div>
                        @error('phone')
                        <span class="text-danger mt-2">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1"><label class="form-label" for="email
                        "><strong>Email</strong></label>
                            <input type="email" class="form-control" id="email" placeholder="Email" name="email" autocomplete="off" required value="{{old('email')}}"/>
                            <div class="invalid-feedback">
                                Please provide an email.
                            </div>
                        </div>
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
                                    <option value="{{$major->id}}">{{$major->name}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a major.
                            </div>
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
                <div class="mb-3 text-end">
                    <a class="btn btn-warning" href="{{route('admin.students.index')}}">Back</a>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('assets/js/validation.js')}}"></script>
@endsection
