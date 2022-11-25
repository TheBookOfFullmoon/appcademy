@extends('layouts.dash_layout')

@section('page_title', 'Lecturer Data')

@section('alert')
    @if (session('success'))
        <div id="success-message" data-message="{{session('success')}}"></div>
    @endif
@endsection

@section('content')
    <div class="card shadow">
        <div class="card-header py-3">
            <div>
                <div class="row">
                    <div class="col align-self-center">
                        <p class="text-primary m-0 fw-bold">Lecturer Data</p>
                    </div>
                    <div class="col text-end"><a href="{{route('admin.lecturers.create')}}" class="btn btn-primary btn-sm text-end"><i class="fas fa-plus"></i>&nbsp;Add Lecturer</a></div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <form action="{{route('admin.lecturers.search')}}" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search Keyword..." aria-label="Example text with button addon" aria-describedby="button-addon1" name="keyword">
                        <button class="btn btn-warning" type="submit" id="button-addon1"><i class="fas fa-search" ></i> Search</button>
                    </div>
                </form>

            </div>
            <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                <table class="table my-0" id="dataTable">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Birth</th>
                        <th>Address</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lecturers as $lecturer)
                        <tr>
                            <td>{{$lecturer->name}}</td>
                            <td>{{$lecturer->birth_place}}, {{$lecturer->birthday}}</td>
                            <td>{{$lecturer->address}}</td>
                            <td>{{$lecturer->gender}}</td>
                            <td>{{$lecturer->user->email}}</td>
                            <td>{{$lecturer->phone}}</td>
                            <td>
                                <form action="{{route('admin.lecturers.destroy', $lecturer->id)}}" method="post">
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-success btn-sm" href="{{route('admin.lecturers.edit', $lecturer->id)}}" style="color: rgb(255,255,255);"><i class="fa fa-pencil"></i></a>

                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm delete" data-name="{{$lecturer->name}}"><i class="fa fa-trash"></i></button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="d-lg-flex justify-content-end">
                    {{$lecturers->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let elements = document.getElementsByClassName("delete");

        let deleteModal = function(e) {
            e.preventDefault();
            let name = this.getAttribute("data-name");
            let form = this.closest("form");

            Swal.fire({
                title: `Are you sure want to delete ${name} data?`,
                text: ``,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }

        Array.from(elements).forEach(function(element) {
            element.addEventListener('click', deleteModal);
        });

        let success = document.getElementById("success-message");

        if (success != null){
            let message = success.getAttribute("data-message");
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: message
            })
        }
    </script>
@endsection
