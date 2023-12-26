@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Class & Subjects</h1>
                    </div>
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

                        <div class="card">
                            {{-- <div class="card-header">
                                <h3 class="card-title">All Assigned Subjects</h3>
                            </div> --}}
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Class Name</th>
                                            <th>Subject Name</th>
                                            <th>Subject Type</th>
                                            <th>Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['getClassSubject'] as $value)
                                            @forelse ($value->assignedClass->subjects as $subject)
                                                <tr>
                                                    <td>{{ $value->assignedClass->name }}</td>
                                                    <td>{{ $subject->name }}</td>
                                                    <td>{{ $subject->type }}</td>
                                                    <td>{{ $value->created_at->format(auth()->user()->date_format) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td>{{ $value->assignedClass->name }}</td>
                                                    <td colspan="3" class="text-center">No Subjects</td>
                                                </tr>
                                            @endforelse


                                            {{-- <tr>
                                                <td>{{ $value->class_name }}</td>
                                                <td>{{ $value->subject_name }}</td>
                                                <td>{{ $value->subject_type }}</td>
                                                <td>{{ $value->created_at->format(auth()->user()->date_format) }}</td>
                                            </tr> --}}
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">No Matching Search Results</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                {{-- <td>
                    @foreach ($value->classSubjectTeacher as $subject)
                        {{ $subject->name }}
                        @if (!$loop->last)
                            , <!-- Add a comma if it's not the last item -->
                        @endif
                    @endforeach
                </td> --}}

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
