@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Contact Teacher</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">

                        @include('_message')

                        <div class="card ">

                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Profile Pic</th>
                                            <th>Teacher Name</th>
                                            <th>Class Name</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['contactTeacher'] as $value)

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
                                                <td>{{ optional($value->assignClassTeacher->classData)->name }}</td>
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
                                    Total ({{ $data['contactTeacher']->total() }})
                                </div>
                                <div style="padding: 10px; float: right">
                                    {{ $data['contactTeacher']->links() }}
                                </div>


                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </section>

    </div>
@endsection
