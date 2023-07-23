@extends('frontend.layout.master')
@section('title','Card List')
@section('content')
<?php $p="cl"; $mp="cf";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                        <thead class="text-center">
                            <tr>
                                <th>Name</th>
                                <th>Cust Ref</th>
                                <th>Card Type</th>
                                <th>Phone Number</th>
                                <th>Balance</th>
                                <th>A.B.N</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>Item Name</td>
                                <td>Bin Number</td>
                                <td>Active/Inactive Item</td>
                                <td>Qun On hand</td>
                                <td>Current Value</td>
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
