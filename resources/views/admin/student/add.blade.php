@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add New Student</h1>
                    </div>

                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">

                        <div class="card card-primary">

                            <form method="post" action="{{ route('admin_student.store') }}" enctype="multipart/form-data"
                                novalidate>
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Name <span style="color:red">*</span></label>
                                            <input type="text" class="form-control" value="{{ old('name') }}"
                                                name="name" required placeholder="Enter Name">
                                            @error('name')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Admission Number <span style="color:red">*</span></label>
                                            <input type="text" class="form-control" value="{{ old('admission_number') }}"
                                                name="admission_number" required placeholder="Enter Admission Number">
                                            @error('admission_number')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror

                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Class <span style="color:red">*</span></label>
                                            <select class="form-control" required name="class_id">
                                                <option value="">Select Class</option>
                                                @foreach ($data['getClass'] as $value)
                                                    <option {{ old('class_id') == $value->id ? 'selected' : '' }}
                                                        value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('class_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror

                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Gender <span style="color:red">*</span></label>
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" name="gender" value="Male"
                                                        {{ old('gender') == 'Male' ? 'checked' : '' }}> Male
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="gender" value="Female"
                                                        {{ old('gender') == 'Female' ? 'checked' : '' }}> Female
                                                </label>
                                            </div>
                                            @error('gender')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label>Date of Birth <span style="color:red">*</span></label>
                                            <input type="date" class="form-control" required
                                                value="{{ old('date_of_birth') }}" name="date_of_birth">
                                            @error('date_of_birth')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror

                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Admission Date <span style="color:red">*</span></label>
                                            <input type="date" class="form-control" value="{{ old('admission_date') }}"
                                                name="admission_date" placeholder="Enter Admission Date" required>
                                            @error('admission_date')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror


                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Profile Picture <span style="color:red"></span></label>
                                            <input type="file" class="form-control" name="profile_pic">
                                            @error('profile_pic')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror

                                        </div>

                                    </div>
                                    <hr />

                                    <div class="form-group">
                                        <label>Email <span style="color:red">*</span></label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email') }}" required placeholder="Enter Email">
                                        @error('email')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Password <span style="color:red">*</span></label>
                                        <input type="password" class="form-control" name="password" required
                                            placeholder="Enter Password">
                                        @error('password')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="confirmpassword">Confirm Password</label>
                                        <input type="password" class="form-control" name="password_confirmation"
                                            placeholder="Confirm Password">
                                        @error('password_confirmation')
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

            </div>
        </section>

    </div>
@endsection
