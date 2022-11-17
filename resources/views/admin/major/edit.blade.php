@extends('layouts.dash_layout')

@section('page_title', 'Edit Major Data')

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
            <p class="text-primary m-0 fw-bold">Major Data</p>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('admin.majors.update', $major->id)}}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-">
                        <div class="mb-3"><label class="form-label" for="username"><strong>Major Name</strong></label><input type="text" class="form-control" id="username" placeholder="Major Name" name="name" autocomplete="off" required value="{{old('name', $major->name)}}"/></div>
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
