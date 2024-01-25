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
                                            <th>Homework ID</th>
                                            <th>Subject</th>
                                            <th>Homework Date</th>
                                            <th>Deadline</th>
                                            <th>Document</th>
                                            <th>Description</th>
                                            <th>Deleted Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($softDeletedRecords as $record)
                                            <tr>
                                                <td>{{ $record->id }}</td>
                                                <td>{{ $record?->subjectData?->name }}</td>
                                                <td>{{ $record->homework_date->format(auth()->user()->date_format) }}</td>
                                                <td>{{ $record->deadline->format(auth()->user()->date_format) }}</td>
                                                <td>
                                                    {{-- {{ $record->getDocument() }} --}}
                                                    @if (!empty($record->getDocument()))
                                                        <a href="{{ $record->getDocument() }}" class="btn btn-success"
                                                            download>Download</a>
                                                    @endif

                                                    @if (!empty($record->getDocument()))
                                                        <a href="{{ $record->getDocument() }}" class="btn btn-secondary"
                                                            target="_blank">View</a>
                                                    @endif
                                                </td>
                                                <td>{{ $record->description }}</td>
                                                <td>{{ $record->deleted_at->format(auth()->user()->date_format) }}</td>
                                                <td>
                                                    <form action="{{ route('homework_teacher.restore', $record->id) }}"
                                                        method="post" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success">Restore</button>
                                                    </form>

                                                    <form action="{{ route('homework_teacher.forceDelete', $record->id) }}"
                                                        method="post" style="display:inline;">
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
                                                <td colspan="100%" class="text-center">No soft deleted records found.</td>
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
