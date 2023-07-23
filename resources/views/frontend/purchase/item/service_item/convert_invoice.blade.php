@extends('frontend.layout.master')
@section('title','Conver Service')
@section('content')
<?php $p="socb"; $mp="purchase"?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                <strong style="color:green; font-size:20px;">Sevice Convert to Enter Bill Item</strong>
                            </div>
                        </div>

                        <br>

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
                                    <th>Viw/Convert</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; @endphp
                                @foreach ($services->groupBy('inv_no') as $service)
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td>{{$service->first()->start_date->format('d/m/Y')}} </td>
                                    <td>{{$service->first()->end_date->format('d/m/Y')}} </td>
                                    <td>{{$service->first()->customer->name}} </td>
                                    <td class="text-center text-primary">PIV#{{$service->first()->inv_no}} </td>
                                    <td>{{$codes->where('code',$service->first()->chart_id)->first()->name}} </td>
                                    <td class="text-right text-info">$ {{number_format($service->sum('amount'),2)}}
                                    </td>
                                    <td>
                                        <div class="action d-flex">
                                            <a href="{{route('service_item.convertView',[$service->first()->client_id,$service->first()->profession_id,$service->first()->inv_no,])}}"
                                                class="btn btn-success btn-sm mx-1">
                                                <i class="fas fa-eye fa-lg"></i> VIEW
                                            </a>
                                            <form action="{{route('service_item.convertStore',[$service->first()->client_id,$service->first()->profession_id,$service->first()->inv_no,])}}" method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-info btn-sm">
                                                <i class="fas fa-truck-moving fa-lg"></i> CONVERT
                                                </button>
                                            </form>
                                        </div>
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
