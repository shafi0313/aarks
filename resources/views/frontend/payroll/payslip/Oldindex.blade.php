@extends('frontend.layout.master')
@section('title','Category')
@section('content')

    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-header">
                                <p>Received Payment</p>
                            </div>

                            <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                                <thead class="text-center" style="font-size: 14px">
                                    <tr>
                                        <th class="center" width="4%">SN</th>
                                        <th>Customer Name</th>
                                        <th>Balance</th>
                                        <th>Enter Receipt</th>
                                        <th>Phone</th>
                                        <th>Mobile</th>
                                        <th>e-mail</th>
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
