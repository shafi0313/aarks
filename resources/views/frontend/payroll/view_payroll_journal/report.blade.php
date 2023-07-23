@extends('frontend.layout.master')
@section('title','Category')
@section('content')
<?php $p="vpj"; $mp="payroll";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">


                            {{-- <div class="table-header mt-3">
                                <p>Transation Journal</p>
                            </div> --}}
                            <table class="table table-bordered table-hover display table-sm">
                                <thead class="text-center" style="font-size: 14px">
                                    <tr>
                                        <th>Date</th>
                                        <th>Account Name</th>
                                        <th>Paticluar</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>06/09/2020</td>
                                        <td>Wages Net pay amount to Employee</td>
                                        <td>Kabir Hasan</td>
                                        <td>2439.92</td>
                                        <td>0.00</td>
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
