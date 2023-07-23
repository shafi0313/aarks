@extends('frontend.layout.master')
@section('title','Debtors Reports')
@section('content')
<?php $p="sl"; $mp="sales";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong style="color:green; font-size:20px;">Debtors Reports: </strong>
                            </div>
                            <div class="col-md-9">
                                <form action="{{route('debtors_report.report')}}" method="get" autocomplete="off">
                                    <input type="hidden" name="client_id" value="{{$client->id}}">
                                    <div class="row justify-content-end">
                                        <div class="col-3 form-group">
                                            <input class="form-control datepicker" data-date-format="dd/mm/yyyy"
                                                value="{{$to_date}}" name="end_date" placeholder="To Date">
                                        </div>
                                        <div class="mr-3">
                                            <button class="btn btn-success" type="submit">Show Report</button>
                                            <button class="btn btn-primary" type="submit">Print/PDF</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <h5 style="margin:0 !important">
                                {{ $client->fullname }}
                            </h5>
                            <u>Debtors Reports as at: {{$to_date}}</u>
                        </div>
                        <table id="examplee" class="table table-striped table-bordered table-hover display table-sm">
                            <thead class="text-center" style="font-size: 14px">
                                <tr>
                                    <th>SL</th>
                                    <th class="text-left">Customer name</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total = 0;
                                @endphp
                                @foreach ($customers as $i => $customer)
                                @php
                                $amt = ($customer->dedotrs->sum('amount') + $customer->opening_blnc) -
                                $customer->dedotrPayments->sum('payment_amount');
                                $total += $amt;
                                @endphp
                                @if ($amt!=0)
                                <tr>
                                    <td>{{($i+1)}}</td>
                                    <td>{{$customer->name}}</td>
                                    <td class="text-right">{{number_format($amt,2)}}</td>
                                </tr>
                                @endif
                                @endforeach
                                <tr>
                                    <td colspan="2" class="text-right text-success">Total:</td>
                                    <td class="text-right text-success">{{number_format($total,2)}}</td>
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
        $('#example').DataTable();
    });
</script>
@stop
