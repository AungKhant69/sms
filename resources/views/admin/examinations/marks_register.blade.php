@extends('layouts.app')

@section('content')
    {{-- @dd($data['getRecord']->total()) --}}
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Marks Register</h1>
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
                                <h3 class="card-title">Search Exams to Register Marks</h3>
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
                                            <a href="{{ route('exam_marks.index') }}" class="btn btn-success"
                                                style="margin-top: 11%">Clear</a>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                        @include('_message')

                        @if (!empty($data['getExamSubject']) && !empty($data['getExamSubject']->count()))

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Register Marks</h3>
                                </div>

                                <div class="card-body p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Student Name</th>
                                                @foreach ($data['getExamSubject'] as $subject)
                                                    <th>{{ $subject->subjectData->name }} <br>
                                                        ({{ $subject->subjectData->type }} : {{ $subject->passing_marks }} /
                                                        {{ $subject->full_marks }})
                                                    </th>
                                                @endforeach

                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($data['getStudentExamClass']) && !empty($data['getStudentExamClass']->count()))
                                                @foreach ($data['getStudentExamClass'] as $student)
                                                    {{-- loop form for each student --}}
                                                    <form method="POST" action="{{ route('exam_marks.store') }}"
                                                        class="SubmitForm">
                                                        @csrf

                                                        <input type="hidden" name="student_id"
                                                            value="{{ $student->id }}">
                                                        <input type="hidden" name="exam_id"
                                                            value="{{ Request::get('exam_id') }}">
                                                        <input type="hidden" name="class_id"
                                                            value="{{ Request::get('class_id') }}">

                                                        <tr>
                                                            <td>{{ $student->name }}</td>
                                                            @foreach ($data['getExamSubject'] as $subject)
                                                                <td>
                                                                    <div style="margin-bottom: 10px;"> Exam Marks
                                                                        <input type="hidden"
                                                                            name="marks[{{ $subject->subjectData->name }}][subject_id]"
                                                                            value="{{ $subject->subjectData->id }}">
                                                                        <input type="text"
                                                                            name="marks[{{ $subject->subjectData->name }}][exam_marks]"
                                                                            style="width: 200px;" placeholder="Enter Marks"
                                                                            class="form-control"
                                                                            value="{{ isset($data['existingMarks'][$student->id][$subject->subjectData->id]['exam_marks'])
                                                                            ? $data['existingMarks'][$student->id][$subject->subjectData->id]['exam_marks'] : '' }}">
                                                                    </div>
                                                                    <div style="margin-bottom: 10px;"> Homework Marks
                                                                        <input type="text"
                                                                            name="marks[{{ $subject->subjectData->name }}][homework_marks]"
                                                                            style="width: 200px;" placeholder="Enter Marks"
                                                                            class="form-control"
                                                                            value="{{ isset($data['existingMarks'][$student->id][$subject->subjectData->id]['homework_marks'])
                                                                            ? $data['existingMarks'][$student->id][$subject->subjectData->id]['homework_marks'] : '' }}">
                                                                    </div>
                                                                </td>
                                                            @endforeach

                                                            <td>
                                                                <button type="submit" class="btn btn-success"
                                                                    onclick="return confirm('Are you sure you want to save?')">Save</button>
                                                            </td>
                                                        </tr>
                                                    </form>
                                                @endforeach
                                            @endif

                                        </tbody>
                                    </table>

                                </div>
                                <!-- /.card-body -->

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
