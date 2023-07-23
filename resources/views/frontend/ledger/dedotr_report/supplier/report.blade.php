@extends('frontend.layout.master')
@section('title','Supplier Reports')
@section('content')
<?php $p="sr"; $mp="purchase";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong style="color:green; font-size:20px;">Supplier Reports: </strong>
                            </div>
                            <div class="col-md-9">
                                <form action="{{route('creditor_report.report')}}" method="get" autocomplete="off">
                                    <input type="hidden" name="client_id" value="{{$client->id}}">
                                    <div class="row justify-content-end">
                                        <div class="col-3 form-group">
                                            <input class="form-control datepicker" data-date-format="dd/mm/yyyy" value="{{$to_date}}"
                                                name="end_date" placeholder="End Date">
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
                                    <th></th>
                                    <th></th>
                                    <th class="text-center" colspan="4">As At</th>
                                </tr>
                                <tr>
                                    <th class="no-short">SL</th>
                                    <th>Customer Name</th>
                                    <th>To Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i=1;
                                    $payment_amount = 0;
                                    $balance = 0;
                                @endphp
                                @foreach ($dedotrs->groupBy('customer_card_id') as $item)
                                @php

                                $toDate = ($item->sum('amount') + $item->first()->customer->opening_blnc );
                                foreach ($item->where('price', '!=', 0)->groupBy('inv_no') as $itemPay) {
                                    $toDate -= $itemPay->first()->payment_amount;
                                }
                                foreach ($item->where('price', 0) as $payment) {
                                    $toDate -= $payment->payment_amount;
                                }

                                @endphp
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$item->first()->customer->name}}</td>
                                    <td>{{number_format($toDate,2)}}</td>
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
        $('#example').DataTable();
    });
</script>
@stop
