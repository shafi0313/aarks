@extends('frontend.layout.master')
@section('title','Manage Purchase')
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
                                <strong style="color:green; font-size:20px;">Service Manage: </strong>
                            </div>
                            <div class="col-md-9">
                                <form action="{{route('edit.print.filter')}}" method="get" autocomplete="off">
                                    <input type="hidden" name="src" value="order">
                                    <div class="row justify-content-end">
                                        <div class="col-3 form-group">
                                            <input class="form-control datepicker" data-date-format="dd/mm/yyyy"
                                                name="start_date" placeholder="From Date">
                                        </div>
                                        <div class="col-3 form-group">
                                            <input class="form-control datepicker" data-date-format="dd/mm/yyyy"
                                                name="end_date" placeholder="To Date">
                                        </div>
                                        <div class="mr-3">
                                            <button class="btn btn-success" type="submit">Show Report</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr>
                        <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                            <thead class="text-center" style="font-size: 14px">
                                <tr>
                                    <th width="4%">SL</th>
                                    <th width="10%">Start Date</th>
                                    <th width="10%">End Date</th>
                                    <th width="15%">Customer Name</th>
                                    <th width="9%">Service No</th>
                                    <th width="9%">Order Amount</th>
                                    <th width="9%">Due Amount</th>
                                    <th width="9%">Paid Amount</th>
                                    <th width="9%" class="no-sort">Action</th>
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
                                    <td class="text-right text-info">$ {{number_format($service->sum('amount'),2)}}
                                    </td>
                                    <td class="text-right text-danger">$
                                        {{number_format($service->sum('amount') - $service->first()->payment_amount,2)}}
                                    </td>
                                    <td class="text-right text-success">$
                                        {{number_format($service->first()->payment_amount,2)}} </td>
                                    <td>
                                        <div class="action">
                                            <a  title="Order Edit" href="{{route('service_order.edit', [$service->first()->profession_id, $service->first()->customer_card_id, $service->first()->inv_no])}}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{route('service_order.destroy',$service->first()->id)}}"
                                                method="post" style="display: inline">
                                                @csrf @method('delete')
                                                <button  title="Order Delete" type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash-alt"></i>
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
<!-- Data Table -->
<script>
    $(document).ready(function() {
            $('#example').DataTable( {
                "lengthMenu": [[50, 100, -1], [50, 100, "All"]],
                "order": [[ 0, "asc" ]]
            } );
        } );
</script>
@stop
