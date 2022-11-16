@extends('layouts.dash_layout')

@section('page_title', 'Admin Dashboard')

@section('alert')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{session('success')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


@endsection

@section('content')

@endsection
