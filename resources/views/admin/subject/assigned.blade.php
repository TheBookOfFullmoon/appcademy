@extends('layouts.dash_layout')

@section('page_title', "Students Assigned to $subject->name")

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
                        <p class="text-primary m-0 fw-bold">Students Data</p>
                    </div>
                    <div class="col text-end">
                        <a href="{{route('admin.subjects.index')}}" class="btn btn-warning btn-sm text-end"><i class="fa fa-arrow-left"></i>&nbsp;Back to Subject Page</a>
                        <a href="{{route('admin.subjects.unassigned', $subject->id)}}" class="btn btn-primary btn-sm text-end"><i class="fas fa-user"></i>&nbsp;Assign Students</a>
                    </div>

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <form action="{{route('admin.subjects.assigned.search', $subject->id)}}" method="GET">
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
                        <th>Student Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{$student->name}}</td>
                            <td>
                                <form action="{{route('admin.subjects.unassign', ['subject' => $subject->id, 'student' => $student->id])}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm unassign" data-student="{{$student->name}}" data-subject="{{$subject->name}}"><i class="fa fa-times"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="d-lg-flex justify-content-end">
                    {{$students->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let elements = document.getElementsByClassName("unassign");

        let unassignModal = function(e) {
            e.preventDefault();
            let studentName = this.getAttribute("data-student");
            let subjectName = this.getAttribute("data-subject");
            let form = this.closest("form");

            Swal.fire({
                title: `Are you sure want to unassign ${studentName} from ${subjectName}?`,
                text: ``,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, unassign it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }

        Array.from(elements).forEach(function(element) {
            element.addEventListener('click', unassignModal);
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
