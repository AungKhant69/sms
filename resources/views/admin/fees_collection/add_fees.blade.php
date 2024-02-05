@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Collect Fees From <span style="color: blue;">({{ $data['getStudent']->name }})</span></h1>
                    </div>

                    <div class="col-sm-6" style="text-align: right;">
                        <button type="button" class="btn btn-primary" id="AddFees">Add Student Fees</button>
                    </div>

                </div>
            </div>
        </section>


        <section class="content">

            <div class="container-fluid">
                <div class="row">


                    <div class="col-md-12">

                        @include('_message')

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Payment Details</h3>
                            </div>

                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Class Name</th>
                                            <th>Total Amount ($)</th>
                                            <th>Paid Amount ($)</th>
                                            <th>Remaining Amount ($)</th>
                                            <th>Payment type</th>
                                            <th>Message</th>
                                            <th>Created By</th>
                                            <th>Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['getFees'] as $value)
                                            <tr>
                                                <td>{{ $value->classData->name }}</td>
                                                <td>$ {{ $value->total_amount }}</td>
                                                <td>$ {{ $value->paid_amount }}</td>
                                                <td>$ {{ $value->remaining_amount }}</td>
                                                <td>{{ $value->payment_type }}</td>
                                                <td>{{ $value->message }}</td>
                                                <td>{{ $value?->createdBy?->name }}</td>
                                                <td>{{ $value->created_at->format(auth()->user()->date_format) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" class="text-center">No Results Found</td>
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

    <div class="modal fade" id="AddFeesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Student Fees</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="AddFeesForm" action="{{ route('fees_collection.store', ['student_id' => $data['getStudent']->id]) }}"
                    method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="col-form-label">Class Name :
                                {{ $data['getStudent']->classData->name }}</label>
                        </div>

                        <div class="form-group">

                            <label class="col-form-label">Total Amount : $
                                {{ $data['getStudent']->classData->fees_amount }}</label>
                            <input type="hidden" name="total_amount"
                                value="{{ $data['getStudent']->classData->fees_amount }}">
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">Paid Amount : $

                                @if (isset($data['getFees'][0]))

                                    {{ number_format($data['getFees'][0]->paid_amount, 2) }}
                                @else
                                    N/A
                                @endif
                            </label>
                        </div>

                        <div class="form-group">
                            @php
                                $remainingAmount = $data['getStudent']->classData->fees_amount - (isset($data['getFees'][0]) ? $data['getFees'][0]->paid_amount : 0);
                            @endphp
                            <label class="col-form-label">Remaining Amount : $
                                {{ number_format($remainingAmount, 2) }}
                            </label>
                            <input type="hidden" name="remaining_amount" value="{{ number_format($remainingAmount, 2) }}">
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">Select Amount to Add <span style="color: red;">*</span></label>
                            <input type="number" class="form-control" name="amount">

                        </div>

                        <div class="form-group">
                            <label class="col-form-label">Payment type <span style="color: red;">*</span></label>
                            <select class="form-control" name="payment_type" required>
                                <option value="">Select</option>
                                <option value="Stripe">Stripe</option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">Message:</label>
                            <textarea class="form-control" name="message"></textarea>

                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript">
        $(document).on('click', '#AddFees', function () {
            // Reset form when the modal is hidden
            $('#AddFeesModal').on('hidden.bs.modal', function () {
                $('#AddFeesForm')[0].reset();
            });

            // Show the modal
            $('#AddFeesModal').modal('show');
        });

        // Add this event listener to prevent form submission on validation errors
        $('#AddFeesForm').submit(function (e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
        });

        // Add event listener for the close buttons
        $(document).on('click', '.modal .btn-secondary, .modal .close', function () {
            $('#AddFeesModal').modal('hide');
        });
    </script>

@endsection


