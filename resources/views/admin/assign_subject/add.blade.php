@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add New Assigned Subject</h1>
                    </div>

                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">

                        <div class="card card-primary">

                            <form method="post" action="{{ route('assign_subject.store') }}">
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

                                    </div>

                                    <div class="form-group">
                                        <label>Subject Name</label>

                                        @foreach ($data['getSubject'] as $subject)
                                            <div>
                                                <label style="font-weight: normal">
                                                    <input type="checkbox" value="{{ $subject->id }}" name="subject_id[]">
                                                    {{ $subject->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                        </select>

                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <div>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" value="1" checked> Active
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" value="0"> Inactive
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </section>

    </div>
@endsection
