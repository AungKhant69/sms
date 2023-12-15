@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
    <section class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Soft Deleted Records</h3>
                        </div>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                        <th>Status</th>
                                        <th>Deleted Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($softDeletedRecords as $record)
                                        <tr>
                                            <td>{{ $record->id }}</td>
                                            <td>{{ $record->name }}</td>
                                            <td>{{ $record->type }}</td>
                                            <td>{{ $record->status }}</td>
                                            <td>{{ $record->deleted_at->format('m-d-Y H:i A') }}</td>
                                            <td>
                                                <form action="{{ route('subject.restore', ['id' => $record->id]) }}" method="post"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">Restore</button>
                                                </form>

                                                <form action="{{ route('subject.forceDelete', ['id' => $record->id]) }}" method="post"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Are you sure you want to permanently delete?')">Force
                                                        Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No soft deleted records found.</td>
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
