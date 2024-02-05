@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Subject</h1>
                    </div>

                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">

                        <div class="card card-primary">

                            <form method="post" action="{{ route('subject.update', ['id' => $data['getRecord']->id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Subject Name</label>
                                        <input type="text" class="form-control" name="name" required
                                            value="{{ old('name', $data['getRecord']->name) }}"
                                            placeholder="Enter Subject Name">
                                        @error('name')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Subject Type</label>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="theoryRadio" value="theory"
                                                {{ old('type', $data['getRecord']->type) == 'theory' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="theoryRadio">Theory</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="practicalRadio" value="practical"
                                                {{ old('type', $data['getRecord']->type) == 'practical' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="practicalRadio">Practical</label>
                                        </div>

                                        @error('type')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>



                                    <div class="form-group">
                                        <label>Status</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" value="1"
                                                {{ $data['getRecord']->status == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label">Active</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" value="0"
                                                {{ $data['getRecord']->status == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label">Inactive</label>
                                        </div>
                                        @error('status')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>

            </div>
        </section>

    </div>
@endsection
