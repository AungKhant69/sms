@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Homework</h1>
                    </div>

                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">

                        @include('_message')

                        <div class="card card-primary">

                            <form method="POST" action="{{ route('homework.update', $data['getRecord']->id) }}" enctype="multipart/form-data" novalidate>
                                @csrf
                                @method('PUT')
                                <div class="card-body">

                                    <div class="form-group col-md-6">
                                        <label>Subject <span style="color: red;">*</span></label>
                                        <select class="form-control" name="subject_id" required>
                                            <option value="">Select Subject</option>
                                            @foreach ($data['getSubject'] as $subject)
                                                <option
                                                    {{ $data['getRecord']->subject_id == $subject->id ? 'selected' : '' }}
                                                    value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('subject_id')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Homework Date <span style="color: red;">*</span></label>

                                        @php
                                            $formattedDate = \Carbon\Carbon::parse($data['getRecord']->homework_date)->format(auth()->user()->date_format);
                                        @endphp

                                        <input type="date" class="form-control" value="{{ $formattedDate }}"
                                            name="homework_date" required>

                                        @error('homework_date')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">

                                        <label>Deadline <span style="color: red;">*</span></label>

                                        @php
                                            $formattedDate = \Carbon\Carbon::parse($data['getRecord']->deadline)->format(auth()->user()->date_format);
                                        @endphp

                                        <input type="date" class="form-control" value="{{ $formattedDate }}"
                                            name="deadline" required>

                                        @error('deadline')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Document (PDF Only) <span style="color: red;">*</span></label>
                                        <input type="file" class="form-control" name="document_file">
                                        @error('document_file')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror

                                        @if ($data['documentFilePath'])
                                            <p>Current File: <a
                                                    href="{{ asset('uploads/homework/' . $data['documentFilePath']) }}"
                                                    target="_blank">{{ basename($data['documentFilePath']) }}</a></p>
                                        @endif
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
                                            // Set the textarea content with old data
                                            $("#compose-textarea").val("{{ old('description', $data['getRecord']->description) }}");

                                            // Variable to track whether cursor has been manually moved
                                            var cursorMoved = false;

                                            // Set cursor position to the end only on the initial select
                                            $("#compose-textarea").on("select", function (event) {
                                                if (!cursorMoved) {
                                                    var selectionStart = this.selectionStart;
                                                    var selectionEnd = this.selectionEnd;

                                                    if (selectionStart === selectionEnd) {
                                                        var textLength = this.value.length;
                                                        this.setSelectionRange(textLength, textLength);
                                                    }
                                                }
                                            });

                                            // Set cursorMoved to true when the user manually moves the cursor
                                            $("#compose-textarea").on("input", function () {
                                                cursorMoved = true;
                                            });

                                            // Reset cursorMoved to false when clicking inside the textarea
                                            $("#compose-textarea").on("mousedown", function () {
                                                cursorMoved = false;
                                            });
                                        });
                                    </script>

                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
    </div>

    </div>

    </div>
    </section>

    </div>
@endsection
