@extends('layouts.app')

@section('content')
    {{-- @dd($data['getRecord']->total()) --}}
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Attendance Report</h1>
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

                                        <div class="form-group col-md-2">
                                            <label>Class Name</label>
                                            <select class="form-control" name="class_id">
                                                <option value="">Select</option>
                                                @foreach ($data['getClass'] as $class)
                                                    <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                        value="{{ $class->id }}">
                                                        {{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Student Name</label>
                                            <input type="text" class="form-control"
                                                value="{{ Request::get('student_name') }}" name="student_name" placeholder="Enter Name">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Attendance Date</label>
                                            <input type="date" class="form-control"
                                                value="{{ Request::get('attendance_date') }}" name="attendance_date">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Attendance Type</label>
                                            <select name="attendance_type" class="form-control">
                                                <option value="">Select</option>
                                                <option {{ Request::get('attendance_type') == 'present' ? 'selected' : '' }}
                                                    value="present">Present</option>
                                                <option {{ Request::get('attendance_type') == 'late' ? 'selected' : '' }}
                                                    value="late">Late</option>
                                                <option {{ Request::get('attendance_type') == 'absent' ? 'selected' : '' }}
                                                    value="absent">Absent</option>
                                                <option
                                                    {{ Request::get('attendance_type') == 'half_day' ? 'selected' : '' }}
                                                    value="half_day">Half day</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button class="btn btn-primary" type="submit"
                                                style="margin-top: 32px;">Search</button>
                                            <a href="{{ route('attendance_report.index') }}" class="btn btn-success"
                                                style="margin-top: 32px;">Clear</a>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                        @include('_message')

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Attendance List</h3>
                            </div>

                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Profile Pic</th>
                                            <th>Student Name</th>
                                            <th>Class Name</th>
                                            <th>Attendance</th>
                                            <th>Attendance Date</th>
                                            <th>Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['getRecord'] as $value)
                                            <tr>
                                                <td>{{ $value->student->id }}</td>
                                                <td>
                                                    {{-- HTML parsing  --}}
                                                    @if (!empty($value->student->profile_pic))
                                                        {!! FormHelper::getProfile($value->student->profile_pic) !!}
                                                    @endif
                                                </td>
                                                <td>{{ $value->student->name }}</td>
                                                <td>{{ $value->classData->name }}</td>
                                                <td>
                                                    @if ($value->attendance_type == 'present')
                                                        Present
                                                    @elseif ($value->attendance_type == 'late')
                                                        Late
                                                    @elseif ($value->attendance_type == 'absent')
                                                        Absent
                                                    @elseif ($value->attendance_type == 'half_day')
                                                        Half-Day
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($value->attendance_date)->format(auth()->user()->date_format) }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($value->created_at)->format(auth()->user()->date_format) }}
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" class="text-center">No Results Found</td>
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
                        </div>




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
