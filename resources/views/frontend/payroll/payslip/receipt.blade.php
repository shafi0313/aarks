@extends('frontend.layout.master')
@section('title','Category')
@section('content')
<?php $p="pep"; $mp="payroll";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong style="color:green; font-size:20px;">Received Payment: </strong>
                            </div>
                            <div class="col-md-9">
                                <div class="row justify-content-end">
                                    <div class="col-3 form-group">
                                        <input class="form-control datepicker" data-date-format="mm/dd/yyyy" name="" placeholder="From Date">
                                    </div>
                                    <div class="col-3 form-group">
                                        <input class="form-control datepicker" data-date-format="mm/dd/yyyy" name="" placeholder="To Date">
                                    </div>
                                    <div class="mr-3">
                                        <button class="btn btn-success" type="submit">Show Report</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <br>

                            <div class="table-header">
                                <p>Data List</p>
                            </div>

                            <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                                <thead class="text-center" style="font-size: 14px">
                                    <tr>
                                        <th width="2%">SN</th>
                                        <th>Customer Name</th>
                                        <th>Transaction Id</th>
                                        <th>Receipt Date</th>
                                        <th>Receipt No</th>
                                        <th>Receipt Amount</th>
                                        <th width="6%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $x=1 @endphp
                                    <tr>
                                        <td class="text-center">{{ $x++ }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
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
