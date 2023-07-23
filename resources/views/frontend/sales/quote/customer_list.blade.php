@extends('frontend.layout.master')
@section('title','Category')
@section('content')
<?php $p="cquotep"; $mp="sales";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong style="color:green; font-size:20px;">Quote Manage: </strong>
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
                                <p>Customer List</p>
                            </div>

                            <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                                <thead class="text-center" style="font-size: 14px">
                                    <tr>
                                        <th width="4%">SL</th>
                                        <th>Customer Name</th>
                                        <th width="11%">Total Amount</th>
                                        <th width="9%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotes as $i=>$quote)
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td>{{$quote->first()->customer->name}} </td>
                                        <td>$ {{number_format($quote->sum('amount'),2)}} </td>
                                        <td>
                                            <a href="{{route('quote.manage',$quote->first()->customer_card_id)}} " class="btn btn-sm btn-primary">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
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
