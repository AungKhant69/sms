@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Contact Parent</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>



        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">

                    <!-- /.col -->
                    <div class="col-md-12">

                        @include('_message')

                        <div class="card ">
                            {{-- <div class="card-header">
                                <h3 class="card-title">All Students</h3>
                            </div> --}}
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Profile Pic</th>
                                            <th>Parent Name</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['contactParent'] as $value)
                                            {{-- @dd($data['getMyStudent']) --}}
                                            <tr>
                                                <td>
                                                    {{-- HTML parsing for profile picture --}}
                                                    @if (!empty($value->profile_pic))
                                                        {!! FormHelper::getProfile($value->profile_pic) !!}
                                                    @else
                                                        <img src="{{ asset('dist/img/default-avatar-profile-icon-grey-photo-placeholder-vector-17317730.jpg') }}"
                                                            alt="Default Image"
                                                            style="height: 50px; width: 50px; border-radius: 50px">
                                                    @endif
                                                </td>
                                                <td>{{ $value->name }}</td>
                                                <td>{{ $value->email }}</td>
                                                <td>
                                                    <a href="{{ route('chat.index', ['receiver_id' => base64_encode($value->id)]) }}"
                                                        class="btn btn-success">Send Message</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No Matching Search Results</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                                <div style="padding: 10px; float: left">
                                    Total ({{ $data['contactParent']->total() }})
                                </div>
                                <div style="padding: 10px; float: right">
                                    {{ $data['contactParent']->links() }}
                                </div>


                            </div>
                            <!-- /.card-body -->
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
