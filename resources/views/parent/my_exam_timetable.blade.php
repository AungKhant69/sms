@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><span style="color: blue;">{{ $data['getStudent']->name }}</span>'s Exam Timetable</h1>
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
                        {{-- @foreach ($data['getRecord'] as $value) --}}
                        <div class="card">
                            {{-- <div class="card-header">
                                    <h3 class="card-title">{{ $value['name'] }}</h3>
                                </div> --}}
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Exam Name</th>
                                            <th>Subject Name</th>
                                            <th>Exam Date</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Room Number</th>
                                            <th>Full Marks</th>
                                            <th>Passing Marks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['getMyStudentTimeTable'] as $exam)
                                            <tr>
                                                <td>{{ $exam['exam']['name'] }}</td>
                                                <td>{{ $exam['subjectData']['name'] }}</td>
                                                <td>{{ $exam['exam_date'] }}</td>
                                                <td>{{ \Carbon\Carbon::parse($exam['start_time'])->format('h:i A') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($exam['end_time'])->format('h:i A') }}</td>
                                                <td>{{ $exam['room_number'] }}</td>
                                                <td>{{ $exam['full_marks'] }}</td>
                                                <td>{{ $exam['passing_marks'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" class="text-center">No Exams announced yet. Please
                                                    continue your studies.</td>
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
