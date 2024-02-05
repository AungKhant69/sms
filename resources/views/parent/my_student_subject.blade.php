@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Subject List</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-6">

                        @include('_message')

                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">My Subjects</h3>
                            </div>

                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Subject Name</th>
                                            <th>Subject Type</th>
                                            <th>Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['getRecord'] as $value)
                                            <tr>
                                                <td>{{ $value?->subjectData?->name }}</td>
                                                <td>{{ $value?->subjectData?->type }}</td>
                                                <td>{{ $value->created_at->format(auth()->user()->date_format) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">No Matching Search Results</td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>



                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </section>

    </div>
@endsection
