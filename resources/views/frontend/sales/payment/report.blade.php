@extends('frontend.layout.master')
@section('title','Invoice')
@section('content')
<?php $p="payment"; $mp="sales";?>

<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-md-7" style="padding:5px;">
                            @php
                                $client = $payment->client;
                                $customer = $payment->customer;
                            @endphp
                            <strong style="font-size:20px;">{{$client->company? $client->company: $client->first_name .' '. $client->last_name}}</strong><br>
                            <strong>A.B.N : {{$client->abn_number}}</strong><br>
                            <strong> {{$client->street_address}}</strong><br>
                            <strong>{{$client->suburb}}</strong><br>
                            <strong>{{$client->state}} {{$client->post_code}}</strong>
                            <strong>Phone: {{$client->phone}}</strong><br>
                            <strong>E-mail: {{$client->email}}</strong><br>
                            <strong>Web : {{$client->website}}</strong>
                        </div>

                        <div class="col-md-5" align="center">
                            <div class="row" style="padding-top:20px;">
                                <div class="col-md-12" align="right">
                                    <img src="{{$client->logo?asset($client->logo):'https://aarks.net.au/upload/logo/indepth.png'}}" class="img-responsive"
                                        style="max-width:180px; max-height:120px;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12"style="font-size:16px; font-weight:800;padding-top:30px;">TO</div>
                            <div class="col-md-8" align="left" style="padding-right:20px;">
                                <div  style="padding:10px; border:2px solid #666666;">
                                    <strong style="font-size:17px;"> {{$customer->name}} </strong><br>
                                    <strong>{{$customer->b_address}}</strong><br>
                                    <strong>{{$customer->b_city}}</strong><br>
                                    <strong>{{$customer->b_state}}</strong>, <strong>{{$customer->b_postcode}}</strong><br>

                                    <strong>Phone : {{$customer->phone}}</strong><br>
                                    <strong>E-mail : {{$customer->email}}</strong>

                                </div>
                            </div>

                            <div class="col-md-4" align="center" style="padding-right:20px;">
                                <div style="padding:10px; border:2px solid #666666; min-height:150px;">
                                    <strong>Receipt</strong><br>
                                    {{$payment->inv_no}}<br><br>
                                    Receipt Date<br>
                                    {{$payment->tran_date->format('d/m/Y')}} </div>
                            </div>


                        <div class="col-md-12" style="padding-top:5px; min-height:300px;">

                            <div align="left"><strong>Receipt Details :</strong></div>

                            <table width="100%" cellpadding="2" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td align="center" width="10%">Invoice</td>

                                        <td align="center" width="15%">Amount</td>
                                    </tr>

                                    <tr>
                                        <td align="center">{{$payment->inv_no}}</td>
                                        <td align="center">{{number_format($payment->payment_amount,2)}}</td>
                                    </tr>
                                    <tr>
                                        <td align="center">Total</td>
                                        <td align="center">{{number_format($payment->payment_amount,2)}}</td>
                                    </tr>
                                    <tr>
                                        {{-- <td></td> --}}
                                        {{-- <td colspan="2">Paid by : Cash at Bank</td> --}}
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12" align="center">
                            Powered by AARKS
                        </div>
                        <div class="col-12" align="center">
                            <a href="{{route('payment.reportPrint', $payment->id)}}" target="_blank" class="btn btn-primary"> Print</a>
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
