@extends('layouts.app')

@section('content')
{{-- @dd($data['getRecord']->total()) --}}
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Admin List </h1>
                    </div>

                    <div class="col-sm-6" style="text-align: right">
                        <a href="{{ route('admin.deletedList') }}" class="btn btn-primary">Show Deleted Admins</a>
                        <a href="{{ route('admin.create') }}" class="btn btn-primary">Create New Admin</a>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>



        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Search Admin</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Name</label>
                                            <input type="text" class="form-control" value="{{ Request::get('name') }}"
                                                name="name"  placeholder="Enter Name">
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
                                           <a href="{{ route('admin.index') }}" class="btn btn-success" style="margin-top: 11%">Clear</a>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                        @include('_message')

                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">All Admins</h3>
                            </div>

                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Profile Pic</th>
                                            <th>Name</th>
                                            <th>Email</th>
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
                                                <td>
                                                    {{-- HTML parsing  --}}
                                                    @if (!empty($record->profile_pic))
                                                        {!! FormHelper::getProfile($record->profile_pic) !!}
                                                    @endif
                                                </td>
                                                <td>{{ $record->name }}</td>
                                                <td>{{ $record->email }}</td>
                                                <td>{{ $record?->createdBy?->name }}</td>
                                                <td>{{ $record?->updatedBy?->name }}</td>
                                                <td>{{ date('m-d-Y H:i A', strtotime($record->created_at)) }}</td>
                                                <td>
                                                    <a href="{{ route('admin.edit', ['id' => $record->id]) }}" class="btn btn-primary">Edit</a>
                                                    {{-- Form for delete action --}}
                                                    <form action="{{ route('admin.destroy', ['id' => $record->id]) }}" method="post" style="display:inline;">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td  colspan="7" class="text-center">No Matching Search Results</td>
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
