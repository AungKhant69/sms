@extends('layouts.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Exam List </h1>
                    </div>

                    <div class="col-sm-6" style="text-align: right">
                        <a href="{{ route('exam.deletedList') }}" class="btn btn-primary">Show Deleted Exams</a>
                        <a href="{{ route('exam.create') }}" class="btn btn-primary">Create New Exam</a>
                    </div>

                </div>
            </div>
        </section>

        <section class="content">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Search Exam</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Exam Name</label>
                                            <input type="text" class="form-control" value="{{ Request::get('name') }}"
                                                name="name"  placeholder="Enter Name">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Date</label>
                                            <input type="date" class="form-control" name="date"
                                                value="{{ Request::get('date') }}"  placeholder="Enter Email">
                                        </div>

                                        <div class="form-group col-md-3">
                                           <button class="btn btn-primary" type="submit" style="margin-top: 11%">Search</button>
                                           <a href="{{ route('exam.index') }}" class="btn btn-success" style="margin-top: 11%">Clear</a>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                        @include('_message')

                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">All Exams</h3>
                            </div>

                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Exam Name</th>
                                            <th>Status</th>
                                            <th>Created By</th>
                                            <th>Updated By</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['getRecord'] as $record)
                                            <tr>
                                                <td>{{ $record->id }}</td>
                                                <td>{{ $record->name }}</td>
                                                <td style="color: {{ $record->status == 1 ? '#4caf50' : 'red' }}">
                                                    @if ($record->status == 1)
                                                        <span
                                                            style="display: inline-block; width: 10px; height: 10px; background-color: #4caf50; border-radius: 50%; margin-left: 5px;"></span>
                                                        Active
                                                    @else
                                                        <span
                                                            style="display: inline-block; width: 10px; height: 10px; background-color: red; border-radius: 50%; margin-left: 5px;"></span>
                                                        Inactive
                                                    @endif
                                                </td>
                                                <td>{{ $record?->createdBy?->name }}</td>
                                                <td>{{ $record?->updatedBy?->name }}</td>
                                                <td>{{ $record->created_at->format(auth()->user()->date_format) }}</td>

                                                <td>
                                                    <a href="{{ route('exam.edit', ['id' => $record->id]) }}" class="btn btn-primary">Edit</a>

                                                    <form action="{{ route('exam.destroy', ['id' => $record->id]) }}" method="post" style="display:inline;">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No Matching Search Results</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                                <div style="padding: 10px; float: left">
                                    Total ({{ $data['getRecord']->total() }})
                                </div>
                                <div style="padding: 10px; float: right">
                                    {{ $data['getRecord']->links() }}
                                </div>


                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </section>

    </div>
@endsection
