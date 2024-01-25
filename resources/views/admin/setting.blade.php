@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Business Email</h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>



        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">

                        @include('_message')

                       <div class="card card-primary">
                            <form action="" method="POST">
                                @csrf
                                <div class="card-body">

                                    <div class="form-group">
                                        <label>My Stripe Email</label>
                                        <input type="email" class="form-control" name="stripe_email" id=""
                                               value="{{ isset($data['getRecord']->stripe_email) ? $data['getRecord']->stripe_email : '' }}"
                                               required placeholder="Business Email">
                                    </div>

                                    <div class="form-group">
                                        <label>Stripe Public Key</label>
                                        <input type="text" class="form-control" name="stripe_key" id=""
                                               value="{{ isset($data['getRecord']->stripe_key) ? $data['getRecord']->stripe_key : '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Stripe Secret Key</label>
                                        <input type="text" class="form-control" name="stripe_secret" id=""
                                               value="{{ isset($data['getRecord']->stripe_secret) ? $data['getRecord']->stripe_secret : '' }}">
                                    </div>



                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
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
