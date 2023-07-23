@extends('frontend.layout.master')
@section('title','Category')
@section('content')
<?php $p="qci"; $mp="sales";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                <strong style="color:green; font-size:20px;">Quote Convert to Invoice</strong>
                            </div>
                        </div>

                        <br>

                            <div class="table-header">
                                <p>Quote Data List</p>
                            </div>

                            <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                                <thead class="text-center" style="font-size: 14px">
                                    <tr>
                                        <th>SL</th>
                                        <th>Date</th>
                                        <th>Expiry Date</th>
                                        <th>Customer Name</th>
                                        <th>Quote NO</th>
                                        <th>Account</th>
                                        <th>Total Amount</th>
                                        <th>Convert</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotes->groupBy('inv_no') as $i=>$quote)
                                    <tr>
                                        <td class="text-center">{{ $i+1 }}</td>
                                        <td>{{$quote->first()->start_date->format('d/m/Y')}} </td><td>{{$quote->first()->end_date != '' ?$quote->first()->end_date->format('d/m/Y'):''}} </td>
                                        <td>{{$quote->first()->customer->name}} </td>
                                        <td>{{$quote->first()->inv_no}} </td>
                                        <td>{{$codes->where('code',$quote->first()->chart_id)->first()->name}} </td>
                                        <td class="text-info">$ {{number_format($quote->sum('amount'),2)}} </td>
                                        <td>
                                            <a href="{{route('quote.convertView',$quote->first()->inv_no)}}" class="btn btn-sm btn-info">VIEW</a>
                                            <a href="{{route('quote.convertStore',$quote->first()->inv_no)}}" class="btn btn-sm btn-primary">Convert</a>
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
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>

@stop
