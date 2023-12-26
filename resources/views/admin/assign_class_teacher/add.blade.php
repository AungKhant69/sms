@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Assign A New Class Teacher</h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">

                        <div class="card card-primary">

                            <form method="post" action="{{ route('assign_class_teacher.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Class Name</label>
                                        <select class="form-control" name="class_id" required>
                                            <option value="">Select Class</option>
                                            @foreach ($data['getClass'] as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>

                                            @endforeach
                                        </select>
                                        @error('class_id')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror

                                    </div>

                                    <div class="form-group">
                                        <label>Teacher Name</label>

                                        @foreach ($data['getTeacher'] as $teacher)
                                            <div>
                                                <label style="font-weight: normal">
                                                    <input type="checkbox" value="{{ $teacher->id }}" name="teacher_id[]">
                                                    {{ $teacher->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                        </select>
                                        @error('teacher_id')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror

                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <div>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" value="1"> Active
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" value="0"> Inactive
                                            </label>
                                        </div>
                                        @error('status')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>


                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
