@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Parent</h1>
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

                            <form method="POST" action="{{ route('admin_parent.update', $data['getRecord']->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Name <span style="color:red">*</span></label>
                                            <input type="text" class="form-control"
                                                value="{{ old('name', $data['getRecord']->name) }}" name="name" required
                                                placeholder="Enter Name">
                                            @error('name')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Gender <span style="color:red">*</span></label>
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" name="gender" value="Male"
                                                        {{ old('gender', $data['getRecord']->gender) == 'Male' ? 'checked' : '' }}>
                                                    Male
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="gender" value="Female"
                                                        {{ old('gender', $data['getRecord']->gender) == 'Female' ? 'checked' : '' }}>
                                                    Female
                                                </label>
                                            </div>
                                            @error('gender')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Address <span style="color:red">*</span></label>
                                            <input type="text" class="form-control"
                                                value="{{ old('address', $data['getRecord']->address) }}" name="address"
                                                required placeholder="Enter Admission Number">
                                            @error('address')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Phone Number <span style="color:red">*</span></label>
                                            <input type="text" class="form-control"
                                                value="{{ old('phone_number', $data['getRecord']->phone_number) }}"
                                                name="phone_number" placeholder="Enter Phone Number">
                                            @error('phone_number')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror

                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Date of Birth <span style="color:red">*</span></label>
                                            <input type="date" class="form-control" required
                                                value="{{ old('date_of_birth', $data['getRecord']->date_of_birth) }}"
                                                name="date_of_birth">
                                            @error('date_of_birth')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror

                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Profile Picture <span style="color:red"></span></label>
                                            <input type="file" class="form-control" name="profile_pic">
                                            @if (!empty($data['getRecord']->profile_pic))
                                                {!! FormHelper::getProfile($data['getRecord']->profile_pic) !!}
                                            @endif

                                            @error('profile_pic')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror

                                        </div>

                                    </div>
                                    <hr />

                                    <div class="form-group">
                                        <label>Email <span style="color:red">*</span></label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email', $data['getRecord']->email) }}" required
                                            placeholder="Enter Email">
                                        @error('email')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Password <span style="color:red">*</span></label>
                                        <input type="password" class="form-control" name="password"
                                            placeholder="Enter Password">
                                        @error('password')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                        <p>If you want to change the password, please add a new password.</p>

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
