@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Class</h1>
          </div>

        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">

            <div class="card card-primary">

              <form method="post" action="{{ url('admin/class/edit/' . $getList->id) }}">
                    @csrf
                    @method('PUT')
                <div class="card-body">
                   <div class="form-group">
                        <label>Class Name</label>
                        <input type="text" class="form-control" name="name" value="{{ $getList->name }}" required placeholder="Enter Class Name">
                      </div>

                      <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option {{ ($getList->status == 1) ? 'selected' : '' }} value="1">Active</option>
                            <option {{ ($getList->status == 0) ? 'selected' : '' }} value="0">Inactive</option>
                        </select>

                      </div>
                </div>


                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Update</button>
                </div>
              </form>
            </div>

          </div>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  @endsection
