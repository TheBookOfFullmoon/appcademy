@extends('layouts.dash_layout')

@section('page_title', 'Create Major Data')

@section('content')
    <div class="card shadow mb-3">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Major Data</p>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('admin.majors.store')}}" class="needs-validation" novalidate>
                @csrf
                <div class="row">
                    <div class="mb-2">
                        <div class="mb-1">
                            <label class="form-label" for="name"><strong>Major Name</strong></label><input type="text" class="form-control" id="name" placeholder="Major Name" name="name" autocomplete="off" required value="{{old('name')}}"/>
                            <div class="invalid-feedback">
                                Please provide a name.
                            </div>
                        </div>
                        @error('name')
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
                    <a class="btn btn-warning" href="{{route('admin.majors.index')}}">Back</a>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('assets/js/validation.js')}}"></script>
@endsection
