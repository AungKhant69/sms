@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Student List</h1>
                    </div>

                    {{-- <div class="col-sm-6" style="text-align: right">
                        <a href="{{ route('admin_parent.deletedList') }}" class="btn btn-primary">Show Deleted Parents</a>
                        <a href="{{ route('admin_parent.create') }}" class="btn btn-primary">Add New Parent</a>
                    </div> --}}

                </div>
            </div><!-- /.container-fluid -->
        </section>


        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">

                    <!-- /.col -->
                    <div class="col-md-12">

                        @include('_message')

                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">Student(s) of this Parent</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Profile Pic</th>
                                            <th>Student Name</th>
                                            <th>Email</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['getRecord'] as $value)
                                            <tr>
                                                <td>{{ $value->id }}</td>
                                                <td>
                                                    {{-- HTML parsing  --}}
                                                    @if (!empty($value->profile_pic))
                                                        {!! FormHelper::getProfile($value->profile_pic) !!}
                                                    @endif
                                                </td>
                                                <td>{{ $value->name }}</td>
                                                <td>{{ $value->email }}</td>
                                                <td>{{ $value->created_at->format(auth()->user()->date_format) }}</td>
                                                <td>
                                                    <a class="btn btn-primary" href="{{ route('parent.ParentStudentSubject', ['student_id' => $value->id]) }}">Subject</a>
                                                    <a href="{{ route('parent.exam_timetable', ['student_id' => $value->id]) }}" class="btn btn-primary">Exam Timetable</a>
                                                    <a href="{{ route('parent.checkHomework', ['student_id' => $value->id]) }}" class="btn btn-primary">Homework</a>
                                                    <a href="{{ route('fees_collection_parent.create', ['student_id' => $value->id]) }}"
                                                        class="btn btn-success">Pay Fees</a>
                                                    <a href="{{ route('chat.index', ['receiver_id' => base64_encode($value->id)]) }}"
                                                        class="btn btn-success">Send Message</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" class="text-center">No Matching Search Results</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                                <div style="padding: 10px; float: left">
                                    Total ({{ $data['getRecord']->total() }})
                                </div>
                                <div style="padding: 10px; float: right">
                                    {{ $data['getRecord']->links() }}
                                </div>


                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
