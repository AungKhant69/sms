@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Assigned Subject</h1>
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

                            <form method="post"
                                action="{{ route('assign_class_teacher.update', ['id' => $data['getRecord']->id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Class Name</label>
                                        <select class="form-control" name="class_id" required>
                                            <option value="">Select Class</option>
                                            @foreach ($data['getClass'] as $class)
                                                <option {{ $data['getRecord']->class_id == $class->id ? 'selected' : '' }}
                                                    value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="form-group">
                                        <label>Teacher Name</label>

                                        @foreach ($data['getTeacher'] as $teacher)
                                           <div>
                                                <label style="font-weight: normal">
                                                    <input @if (in_array($teacher->id, $data['assignTeacherIDs'])) checked  @endif
                                                    type="checkbox" value="{{ $teacher->id }}" name="teacher_id[]">
                                                    {{ $teacher->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>


                                    <div class="form-group">
                                        <label>Status</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" value="1" {{ ($data['getRecord']->status == 1) ? 'checked' : '' }}>
                                            <label class="form-check-label">Active</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" value="0" {{ ($data['getRecord']->status == 0) ? 'checked' : '' }}>
                                            <label class="form-check-label">Inactive</label>
                                        </div>
                                    </div>

                                </div>


                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
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
