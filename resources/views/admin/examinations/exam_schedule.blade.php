@extends('layouts.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Exam Schedule</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Search Exam Schedule</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Exam Name</label>
                                            <select class="form-control" name="exam_id" required>
                                                <option value="">Select</option>
                                                @foreach ($data['getExamList'] as $exam)
                                                    <option {{ Request::get('exam_id') == $exam->id ? 'selected' : '' }}
                                                        value="{{ $exam->id }}">
                                                        {{ $exam->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Class Name</label>
                                            <select class="form-control" name="class_id" required>
                                                <option value="">Select</option>
                                                @foreach ($data['getClass'] as $class)
                                                    <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                        value="{{ $class->id }}">
                                                        {{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button class="btn btn-primary" type="submit"
                                                style="margin-top: 11%">Search</button>
                                            <a href="{{ route('exam_schedule.index') }}" class="btn btn-success"
                                                style="margin-top: 11%">Clear</a>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                        @include('_message')

                    @if (!empty($data['getResult']))
                            <form method="POST" action="{{ route('exam_schedule.store') }}">
                                @csrf
                                <input type="hidden" name="exam_id" value="{{ Request::get('exam_id') }}">
                                <input type="hidden" name="class_id" value="{{ Request::get('class_id') }}">

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Set Exam Schedule</h3>
                                    </div>

                                    <div class="card-body p-0">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
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

                                                @forelse ($data['getResult'] as $index => $value)
                                                <tr>
                                                    <td>{{ $value['subject_name'] }}
                                                        <input type="hidden" class="form-control" value="{{ $value['subject_id'] }}"
                                                            name="schedule[{{ $index + 1 }}][subject_id]">
                                                    </td>
                                                    <td>
                                                        <input type="date" class="form-control" value="{{ $value['exam_date'] }}"
                                                        name="schedule[{{ $index + 1 }}][exam_date]">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" value="{{ $value['start_time'] }}"
                                                        name="schedule[{{ $index + 1 }}][start_time]">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" value="{{ $value['end_time'] }}"
                                                        name="schedule[{{ $index + 1 }}][end_time]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" value="{{ $value['room_number'] }}"
                                                        name="schedule[{{ $index + 1 }}][room_number]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" value="{{ $value['full_marks'] }}"
                                                        name="schedule[{{ $index + 1 }}][full_marks]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" value="{{ $value['passing_marks'] }}"
                                                        name="schedule[{{ $index + 1 }}][passing_marks]">
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No matching search results</td>
                                                </tr>

                                                @endforelse

                                            </tbody>
                                        </table>
                                        <div style="text-align: center; padding: 20px;">
                                            <button class="btn btn-primary">Submit</button>
                                        </div>

                                    </div>

                        @endif
                    </div>

                    </form>

                </div>

            </div>

    </div>
    </section>
    </div>
@endsection
