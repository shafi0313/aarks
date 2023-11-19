@extends('frontend.layout.master')
@section('title','Supplier')
@section('content')
<?php $p="epeb"; $mp="purchase";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong style="color:green; font-size:20px;">Edit/Print/Email Bill: </strong>
                            </div>
                            <div class="col-md-9">
                                <form action="{{route('edit.print.filter')}}" method="get" autocomplete="off">
                                    <input type="hidden" name="src" value="bill">
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
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Tran Id</th>
                                    <th>Supplier Name</th>
                                    <th>Order No</th>
                                    <th>Order Amount</th>
                                    <th>Due Amount</th>
                                    <th>Paid Amount</th>
                                    <th width="130px" class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; @endphp
                                @foreach ($services->groupBy('inv_no') as $service)
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td>{{$service->first()->tran_date->format('d/m/Y')}} </td>
                                    <td>{{$service->first()->tran_id}} </td>
                                    <td>{{$service->first()->customer->name}} </td>
                                    <td class="text-center text-primary">
                                        <a
                                            href="{{route('bill.report',['service',$service->first()->inv_no,$client->id])}}">{{invoice($service->first()->inv_no,
                                            8, 'ODR')}}</a>
                                    </td>
                                    <td class="text-info">$ {{number_format($service->sum('amount'),2)}}
                                    </td>

                                    <td class="text-danger">$
                                        {{number_format($service->sum('amount') -
                                        $service->first()->payments->sum('payment_amount'),2)}}
                                    </td>
                                    <td class="text-success">$
                                        {{number_format($service->first()->payments->sum('payment_amount'),2)}} </td>
                                    <td>
                                        <div class="action">
                                            <a  title="Bill Print" href="{{route('bill.report',['service',$service->first()->inv_no, $client->id])}}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fa fa-print"></i>
                                            </a>
                                            {{-- <a  title="Bill Mail" href="{{route('bill.report.mail',['service',$service->first()->inv_no, $client->id])}}"
                                                class="btn btn-warning btn-sm">
                                                <i class="far fa-envelope"></i>
                                            </a> --}}

                                            <a title="BILL Mail"
                                                href="{{route('bill.viewable_mail',['service',$service->first()->inv_no,$client->id])}}"
                                                class="btn btn-primary btn-sm">
                                                <i class="far fa-envelope-open"></i>
                                            </a>
                                            <a  title="Bill Edit" href="{{route('service_bill.billedit',[$service->first()->inv_no, $client->id])}}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{route('service_bill.destroy',$service->first()->id)}}"
                                                method="post" style="display: inline">
                                                @csrf @method('delete')
                                                <button  title="Bill Delete" type="submit" class="btn btn-sm btn-danger"
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
