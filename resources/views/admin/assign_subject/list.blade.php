@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Assign Subject List</h1>
                    </div>

                    <div class="col-sm-6" style="text-align: right">
                        <a href="{{ url('admin/assign_subject/deleted_list') }}" class="btn btn-primary">Show Deleted Assigned
                            Subjects</a>
                        <a href="{{ url('admin/assign_subject/add') }}" class="btn btn-primary">Assign a new Subject</a>
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
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Search Assigned Subject</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Class Name</label>
                                            <input type="text" class="form-control"
                                                value="{{ Request::get('class_name') }}" name="class_name"
                                                placeholder="Enter Class Name">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Subject Name</label>
                                            <input type="text" class="form-control"
                                                value="{{ Request::get('subject_name') }}" name="subject_name"
                                                placeholder="Enter Subject Name">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Date</label>
                                            <input type="date" class="form-control" name="date"
                                                value="{{ Request::get('date') }}" placeholder="Enter Email">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button class="btn btn-primary" type="submit"
                                                style="margin-top: 11%">Search</button>
                                            <a href="{{ url('admin/assign_subject/list') }}" class="btn btn-success"
                                                style="margin-top: 11%">Clear</a>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>



                        @include('_message')

                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">All Assigned Subjects</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Class Name</th>
                                            <th>Subject Name</th>
                                            <th>Status</th>
                                            <th>Created By</th>
                                            <th>Updated By</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['getRecord'] as $value)
                                            <tr>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value?->classData?->name }}</td>
                                                <td>{{ $value?->subjectData?->name }}</td>

                                                <td>
                                                    @if ($value->status == 1)
                                                        Active
                                                    @else
                                                        Inactive
                                                    @endif
                                                </td>
                                                <td>{{ $value?->createdBy?->name }}</td>
                                                <td>{{ $value?->updatedBy?->name }}</td>
                                                <td>{{ date('m-d-Y H:i A', strtotime($value->created_at)) }}</td>
                                                <td>
                                                    <a href="{{ route('assign_subject.edit', ['id' => $value->id]) }}"
                                                        class="btn btn-primary">Edit</a>

                                                    <form
                                                        action="{{ route('assign_subject.delete', ['id' => $value->id]) }}"
                                                        method="post" style="display:inline;">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete?')">Delete</button>
                                                    </form>
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
