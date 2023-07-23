@extends('frontend.layout.master')
@section('title','Category')
@section('content')
<?php $p="sol"; $mp="purchase";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                        <div class="row">
                            <div class="col-md-3">
                                <strong style="color:green; font-size:20px;">Service Order Manage: </strong>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-5 form-group">
                                        <input class="form-control" type="date" name="" placeholder="From Date">
                                    </div>
                                    <div class="col-5 form-group">
                                        <input class="form-control" type="date" name="" placeholder="To Date">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-success btn-sm" type="submit">Show Report</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <br>

                            <div class="table-header">
                                <p>Data List</p>
                            </div>

                            <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                                <thead class="text-center">
                                    <tr>
                                        <th>SN</th>
                                        <th>Date</th>
                                        <th>Expiry Date</th>
                                        <th>Supplier Name</th>
                                        <th>Service NO</th>
                                        <th>Total Amount</th>
                                        <th>Action</th>
                                        <th>Print/E-mail</th>
                                        <th>Action</th>
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
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>

@stop
