@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Homework List</h1>
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

                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>

                                            <th>Subject Name</th>
                                            <th>Homework Date</th>
                                            <th>Deadline</th>
                                            <th>Document</th>
                                            <th>Description</th>
                                            <th>Created By</th>
                                            <th>Created At</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['getRecord'] as $value)
                                            <tr>
                                                <td>{{ $value?->subjectData?->name }}</td>
                                                <td>{{ $value->homework_date->format(auth()->user()->date_format) }}</td>
                                                <td>{{ $value->deadline->format(auth()->user()->date_format) }}</td>
                                                <td>

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

                        </div>

                    </div>

                </div>

            </div>
        </section>

    </div>
@endsection
