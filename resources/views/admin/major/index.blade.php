@extends('layouts.dash_layout')

@section('page_title', 'Major Data')

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
                        <p class="text-primary m-0 fw-bold">Major Data</p>
                    </div>
                    <div class="col text-end"><a href="{{route('admin.majors.create')}}" class="btn btn-primary btn-sm text-end"><i class="fas fa-plus"></i>&nbsp;Add Major</a></div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <form action="{{route('admin.majors.search')}}" method="GET">
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
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($majors as $major)
                        <tr>
                            <td>{{$major->name}}</td>
                            <td>
                                <form action="{{route('admin.majors.destroy', $major->id)}}" method="post">
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-success btn-sm" href="{{route('admin.majors.edit', $major->id)}}" style="color: rgb(255,255,255);"><i class="fa fa-pencil"></i></a>

                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm delete" data-name="{{$major->name}}" data-student="{{$major->students->count()}}"><i class="fa fa-trash"></i></button>
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
                    {{$majors->links()}}
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
            let studentCount = this.getAttribute("data-student")
            let form = this.closest("form");

            Swal.fire({
                title: `Are you sure want to delete ${name} data?`,
                text: `There are ${studentCount} student data that will also deleted!`,
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
