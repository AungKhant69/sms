@extends('layouts.app')

@section('content')
    {{-- @dd($data['getRecord']->total()) --}}
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Student Attendance</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>



        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Search Student Attendance</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">

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
                                            <label>Attendance Date</label>
                                            <input type="date" class="form-control"
                                                value="{{ Request::get('attendance_date') }}" required
                                                name="attendance_date">
                                        </div>


                                        <div class="form-group col-md-3">
                                            <button class="btn btn-primary" type="submit"
                                                style="margin-top: 11%">Search</button>
                                            <a href="{{ route('attendance_teacher.index') }}" class="btn btn-success"
                                                style="margin-top: 11%">Clear</a>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                        @include('_message')

                        @if (!empty(Request::get('class_id')) && !empty(Request::get('attendance_date')))

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Student List</h3>
                                </div>

                                <div class="card-body p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Student ID</th>
                                                <th>Profile Pic</th>
                                                <th>Student Name</th>
                                                <th>Attendance</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($data['getStudent']) && !empty($data['getStudent']->count()))
                                                @foreach ($data['getStudent'] as $value)
                                                    <form method="POST" action="{{ route('attendance_teacher.store') }}" class="SubmitAttendance">
                                                        @csrf

                                                        <input type="hidden" name="class_id" value="{{ Request::get('class_id') }}">
                                                        <input type="hidden" name="attendance_date" value="{{ Request::get('attendance_date') }}">
                                                        <input type="hidden" name="student_id" value="{{ $value->id }}">

                                                        <tr>
                                                            <td>{{ $value->id }}</td>
                                                            <td>
                                                                {{-- HTML parsing  --}}
                                                                @if (!empty($value->profile_pic))
                                                                    {!! FormHelper::getProfile($value->profile_pic) !!}
                                                                @endif
                                                            </td>
                                                            <td>{{ $value->name }}</td>
                                                            <td>
                                                                <label style="margin-right: 10px;">
                                                                    <input type="radio" value="present" name="attendance_type[{{ $value->id }}][attendance]"
                                                                        {{ $data['existingAttendance']->where('student_id', $value->id)->pluck('attendance_type')->first() == 'present' ? 'checked' : '' }}>
                                                                    Present
                                                                </label>
                                                                <label style="margin-right: 10px;">
                                                                    <input type="radio" value="late" name="attendance_type[{{ $value->id }}][attendance]"
                                                                        {{ $data['existingAttendance']->where('student_id', $value->id)->pluck('attendance_type')->first() == 'late' ? 'checked' : '' }}>
                                                                    Late
                                                                </label>
                                                                <label style="margin-right: 10px;">
                                                                    <input type="radio" value="absent" name="attendance_type[{{ $value->id }}][attendance]"
                                                                        {{ $data['existingAttendance']->where('student_id', $value->id)->pluck('attendance_type')->first() == 'absent' ? 'checked' : '' }}>
                                                                    Absent
                                                                </label>
                                                                <label>
                                                                    <input type="radio" value="half_day" name="attendance_type[{{ $value->id }}][attendance]"
                                                                        {{ $data['existingAttendance']->where('student_id', $value->id)->pluck('attendance_type')->first() == 'half_day' ? 'checked' : '' }}>
                                                                    Half Day
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <button type="submit" class="btn btn-success"
                                                                    onclick="return confirm('Are you sure you want to submit attendance?')">Submit</button>
                                                            </td>
                                                        </tr>
                                                    </form>
                                                @endforeach
                                            @endif
                                        </tbody>


                                    </table>
                                </div>
                            </div>
                        @endif



                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
