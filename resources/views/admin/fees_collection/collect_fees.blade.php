@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Fees Collection</h1>
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
                                <h3 class="card-title">Search Students to collect fees</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Class</label>
                                            <select name="class_id" class="form-control">
                                                <option value="">Select Class</option>
                                                @foreach ($data['getClass'] as $class)
                                                    <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                        value="{{ $class->id }}">
                                                        {{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- <div class="form-group col-md-2">
                                            <label>Student Name</label>
                                            <input type="text" class="form-control" value="{{ Request::get('student_name') }}"
                                                name="student_name" placeholder="Enter Name">
                                        </div> --}}

                                        <div class="form-group col-md-3">
                                            <button class="btn btn-primary" type="submit"
                                                style="margin-top: 11%">Search</button>
                                            <a href="{{ route('fees_collection.index') }}" class="btn btn-success"
                                                style="margin-top: 11%">Clear</a>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                        @include('_message')

                        @if (!empty($data['getRecord']))
                            <div class="card ">
                                <div class="card-header">
                                    <h3 class="card-title">Students List</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Student ID</th>
                                                <th>Student Name</th>
                                                <th>Total Amount ($)</th>
                                                <th>Paid Amount ($)</th>
                                                <th>Remaining Amount ($)</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @dd($data) --}}
                                            @forelse ($data['getRecord'] as $value)
                                                @php
                                                    $paid_amount = $value->getPaidAmount($value->id, $value->classData->id);

                                                    $remainingAmount = $value?->classData?->fees_amount - $paid_amount;

                                                @endphp
                                                <tr>
                                                    <td>{{ $value->id }}</td>
                                                    <td>{{ $value->name }}</td>
                                                    <td>$ {{ $value?->classData?->fees_amount }}</td>
                                                    <td>$ {{ number_format($paid_amount, 2) }}</td>
                                                    <td>$ {{ number_format($remainingAmount, 2) }}</td>
                                                    <td>{{ $value->created_at->format(auth()->user()->date_format) }}</td>
                                                    <td>
                                                        @if ($remainingAmount == 0)
                                                        <div style="background-color: #4caf50; width: 150px; padding: 5px; border-radius: 5px; display: flex; align-items: center;">
                                                            <div style="background-color: green; width: 10px; height: 10px; border-radius: 50%; margin-right: 5px;"></div>
                                                            <div style="text-align: center; color: white;">All fees received</div>
                                                        </div>
                                                        @else
                                                        <a href="{{ route('fees_collection.create', ['student_id' => $value->id]) }}"
                                                            class="btn btn-success">Collect Fees</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%" class="text-center">No Matching Search Results</td>
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
                        @endif
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
