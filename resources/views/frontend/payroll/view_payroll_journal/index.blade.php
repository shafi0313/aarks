@extends('frontend.layout.master')
@section('title','Category')
@section('content')
<?php $p="vpj"; $mp="payroll";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justiy-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <strong style="color:green; font-size:20px;">View Payroll Journal: </strong>
                                </div>

                                <div class="form-inline form-group">
                                    <label for="">From: </label>
                                    <input class="form-control mx-sm-3 datepicker" data-date-format="mm/dd/yyyy" name="" placeholder="From Payment Date">
                                </div>

                                <div class="form-inline form-group">
                                    <label for="">To: </label>
                                    <input class="form-control mx-sm-3 datepicker" data-date-format="mm/dd/yyyy" name="" placeholder="To Payment Date">
                                </div>

                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-success">Show Report</button>
                                </div>

                            </div>
                            <div class="table-header mt-3">
                                <p>Transation Journal</p>
                            </div>
                            <table id="example" class="table table-bordered table-hover display table-sm">
                                <thead class="text-center" style="font-size: 14px">
                                    <tr>
                                        <th>SN</th>
                                        <th>Employee Name</th>
                                        <th>Payment Date</th>
                                        <th>Translation ID</th>
                                        <th>Translation View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <a href="{{ route('view_payroll_journal_report')}}">View</a>
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

    <!-- Footer Start -->

    <!-- Footer End -->


    <!-- inline scripts related to this page -->
    <!-- Data Table -->
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>
@stop
