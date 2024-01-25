@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create New Homework</h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">

                        @include('_message')

                        <div class="card card-primary">
                            {{-- {{ route('admin.store') }} --}}
                            <form method="POST" action="{{ route('homework_teacher.store') }}" enctype="multipart/form-data" novalidate>
                                @csrf
                                <div class="card-body">

                                        {{-- <div class="form-group col-md-6">
                                            <label>Class <span style="color: red;">*</span></label>
                                            <select class="form-control" name="class_id" required>
                                                <option value="">Select Class</option>
                                                @foreach ($data['getClass'] as $class)
                                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('class_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div> --}}

                                        <div class="form-group col-md-6">
                                            <label>Subject <span style="color: red;">*</span></label>
                                            <select class="form-control" name="subject_id" required>
                                                <option value="">Select Subject</option>
                                                @foreach ($data['getSubject'] as $subject)
                                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('subject_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Homework Date <span style="color: red;">*</span></label>
                                            <input type="date" class="form-control" name="homework_date" required>
                                            @error('homework_date')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Deadline <span style="color: red;">*</span></label>
                                            <input type="date" class="form-control" name="deadline" required>
                                            @error('deadline')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Document (PDF Only) <span style="color: red;">*</span></label>
                                            <input type="file" class="form-control" name="document_file" required>
                                            @error('document_file')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6" id="summernote-container">
                                            <label>Description</label>
                                            <textarea id="compose-textarea" class="form-control" name="description" style="height: 150px;"></textarea>
                                            @error('description')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                                        <script>
                                            $(document).ready(function () {
                                                // Set cursor position to the beginning when the textarea is clicked
                                                $("#compose-textarea").on("click", function () {
                                                    this.setSelectionRange(0, 0);
                                                });
                                            });
                                        </script>
                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
    </div>

    </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
@endsection
