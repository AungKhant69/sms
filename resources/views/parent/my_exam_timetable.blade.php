@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><span style="color: blue;">{{ $data['getStudent']->name }}</span>'s Exam Timetable</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">

                        @include('_message')

                        <div class="card">

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

                        </div>

                    </div>

                </div>

            </div>
        </section>
    </div>
@endsection
