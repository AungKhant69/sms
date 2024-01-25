@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Homework List</h1>
                    </div>

                    <div class="col-sm-6" style="text-align: right">
                        <a href="{{ route('homework.deletedList') }}" class="btn btn-primary">Show Deleted Homeworks</a>
                        <a href="{{ route('homework.create') }}" class="btn btn-primary">Add New Homework</a>
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
                        {{-- <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Search Homework</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Subject</label>
                                            <input type="text" class="form-control" value="{{ Request::get('subject') }}"
                                                name="subject"  placeholder="Enter Subject">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Email</label>
                                            <input type="text" class="form-control" name="email"
                                                value="{{ Request::get('email') }}"  placeholder="Enter Email">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Date</label>
                                            <input type="date" class="form-control" name="date"
                                                value="{{ Request::get('date') }}"  placeholder="Enter Email">
                                        </div>

                                        <div class="form-group col-md-3">
                                           <button class="btn btn-primary" type="submit" style="margin-top: 11%">Search</button>
                                           <a href="{{ route('homework.index') }}" class="btn btn-success" style="margin-top: 11%">Clear</a>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div> --}}

                        @include('_message')

                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">All Homeworks</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Homework ID</th>
                                            <th>Subject</th>
                                            <th>Homework Date</th>
                                            <th>Deadline</th>
                                            <th>Document</th>
                                            <th>Description</th>
                                            <th>Created By</th>
                                            <th>Updated By</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['getRecord'] as $value)
                                            <tr>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value?->subjectData?->name }}</td>
                                                <td>{{ $value->homework_date->format(auth()->user()->date_format) }}</td>
                                                <td>{{ $value->deadline->format(auth()->user()->date_format) }}</td>
                                                <td>
                                                    {{-- {{ $value->getDocument() }} --}}
                                                    @if (!empty($value->getDocument()))
                                                        <a href="{{ $value->getDocument() }}" class="btn btn-success"
                                                            download>Download</a>
                                                    @endif

                                                    @if (!empty($value->getDocument()))
                                                        <a href="{{ $value->getDocument() }}" class="btn btn-secondary"
                                                            target="_blank">View</a>
                                                    @endif
                                                </td>
                                                <td>{{ $value->description }}</td>
                                                <td>{{ $value?->createdBy?->name }}</td>
                                                <td>{{ $value?->updatedBy?->name }}</td>
                                                <td>{{ $value->created_at->format(auth()->user()->date_format) }}</td>
                                                <td>
                                                    <a href="{{ route('homework.edit', ['id' => $value->id]) }}"
                                                        class="btn btn-primary">Edit</a>
                                                        <form action="{{ route('homework.destroy', ['id' => $value->id]) }}" method="post" style="display:inline;">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete?')">Delete</button>
                                                        </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%">No Results Found</td>
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
