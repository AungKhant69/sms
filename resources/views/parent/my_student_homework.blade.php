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
                                <h3 class="card-title"> Homeworks</h3>
                            </div> --}}
                            <!-- /.card-header -->
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            {{-- <th>Homework ID</th> --}}
                                            <th>Subject Name</th>
                                            <th>Homework Date</th>
                                            <th>Deadline</th>
                                            <th>Document</th>
                                            <th>Description</th>
                                            <th>Created By</th>
                                            <th>Created At</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['getRecord'] as $value)
                                            <tr>
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
                                                <td>{{ $value->created_at->format(auth()->user()->date_format) }}</td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" class="text-center">No Homeworks given yet.</td>
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
