@extends('frontend.layout.master')
@section('title','Manage Deduction')
@section('content')
<?php $p="mw"; $mp="payroll";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading d-flex">
                                <p>Deduction</p>
                                <button type="button" class="btn btn-warning ml-auto" data-toggle="modal" data-target="#exampleModal">
                                    Add Deduction Category
                                </button>
                            </div>

                            <table id="example" class="table table-bordered table-hover table-sm display">
                                <thead class="text-center" style="font-size: 14px">
                                    <tr>
                                        <th width="4%">SL</th>
                                        <th>Deduction Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $x=1 @endphp
                                    <tr>
                                        <td class="text-center">{{ $x++ }}</td>
                                        <td></td>
                                        <td>
                                            <div class="action">
                                                <a href="" class="trash">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->

 <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" >
      <div class="modal-content">
        <div class="modal-header card-heading text-light">
          <h5 class="modal-title  " id="exampleModalLabel">Add New Deducation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Superannuation Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="name" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="e_rate" class="col-sm-6 col-form-label text-right">Equals</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm" name="e_rate" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="e_tools" class="col-sm-6 col-form-label text-right">Percent of</label>
                        <div class="col-sm-6">
                            <select class="custom-select custom-select-sm" name="e_tools">
                                <option value="BS" selected>Basic Sallary</option>
                                <option value="GH">Gross Hours</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="e_fix_amt" class="col-sm-6 col-form-label text-right">Equals</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm" name="e_fix_amt" id="" placeholder="">
                        </div>
                    </div>

                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="e_period" class="col-sm-6 col-form-label text-right">Dollars Per</label>
                        <div class="col-sm-6">
                            <select class="custom-select custom-select-sm" name="e_period">
                                <option value="Pay Period" selected>Pay Period</option>
                                <option value="Per Hours">Per Hours</option>
                                <option value="Per Month">Per Month</option>
                                <option value="Per Forthnighty">Per Forthnighty</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                        <label class="form-check-label" for="defaultCheck1">
                            No Limit
                        </label>
                      </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="t_rate" class="col-sm-6 col-form-label text-right">Equals</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm" name="t_rate" id="" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="t_tools" class="col-sm-6 col-form-label text-right">Percent of</label>
                        <div class="col-sm-6">
                            <select class="custom-select custom-select-sm" name="t_tools">
                                <option value="BS" selected>Basic Sallary</option>
                                <option value="GH">Gross Hours</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="t_fix_amt" class="col-sm-6 col-form-label text-right">Equals</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm" name="t_fix_amt" id="" placeholder="">
                        </div>
                    </div>

                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="t_period" class="col-sm-6 col-form-label text-right">Dollars Per</label>
                        <div class="col-sm-6">
                            <select class="custom-select custom-select-sm" name="t_period">
                                <option value="Pay Period" selected>Pay Period</option>
                                <option value="Per Hours">Per Hours</option>
                                <option value="Per Month">Per Month</option>
                                <option value="Per Forthnighty">Per Forthnighty</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>


    <!-- Footer Start -->

    <!-- Footer End -->

    <!-- inline scripts related to this page -->
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>

@stop
