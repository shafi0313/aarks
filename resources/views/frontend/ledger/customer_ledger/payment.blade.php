@extends('frontend.layout.master')
@section('title','Customer Ledger')
@section('content')
<?php $p="cled"; $mp="report";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="row" style="border:2px solid #666666; min-height:600px; background:#FFFFFF;">
                    <div class="col-md-7" style="padding-left:11px;">
                        <div class="">
                            <div style="padding:5px;">
                                <strong style="font-size:20px;">{{$dedotr->client->fullname}}</strong><br>
                                <strong>A.B.N : {{$dedotr->client->abn_number}}</strong><br>
                                <strong>{{$dedotr->client->street_address}}</strong><br>
                                <strong>{{$dedotr->client->suburb}}</strong><br>
                                <strong>{{$dedotr->client->state}} {{$dedotr->client->post_code}}</strong><br>
                                <strong>Phone: {{$dedotr->client->phone}}.</strong><br>
                                <strong>E-mail: {{$dedotr->client->email}}</strong><br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5" align="center">
                        <div class="row" style="padding-top:20px;">
                            <div class="col-md-12" align="right">
                                <img src="{{logo()}}"
                                    style="max-width:180px; max-height:120px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-top:0px;">
                        <div style="font-size:16px; font-weight:800;">TO</div>
                        <div class="row">
                            <div class="col-md-8" align="left" style="padding-right:20px;">
                                <div style="padding:10px; border:2px solid #666666;">
                                    <p><strong style="font-size:16px;">{{$dedotr->customer->name}}</strong>
                                         <br>
                                    {{$dedotr->customer->b_address}} <br>
                                    {{$dedotr->customer->b_city}} <br>
                                    {{$dedotr->customer->b_state}}, <b>Post: </b>{{$dedotr->customer->b_postcode}} <br>
                                    <b>Phone: </b>{{$dedotr->customer->phone}} <br>
                                    <b>E-mail: </b>{{$dedotr->customer->email}}</p>
                                </div>
                            </div>
                            <div class="col-md-4" align="center" style="padding-right:20px;">
                                <div class="row" style="padding:10px; border:2px solid #666666; min-height:150px;">
                                    <strong>Receipt</strong><br>
                                    {{$dedotr->inv_no}}<br><br>
                                    Receipt Date<br>
                                    {{$dedotr->tran_date->format('d/m/Y')}}
                                </div>
                            </div>
                        </div>
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
                                    <td align="center">{{$dedotr->inv_no}}</td>
                                    <td align="center">{{number_format($dedotrs->sum('payment_amount'),2)}}</td>
                                </tr>
                                <tr>
                                    <td align="center">Total Amount</td>
                                    <td align="center">$ {{number_format($dedotrs->sum('payment_amount'),2)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Paid by : F&amp;F Bank account</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12 text-center">
                        <p>Powered by AARKS</p>
                        <a href="{{route('payment.reportPrint', $dedotr->id)}}" target="_blank" class="btn btn-primary"> Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->

@stop
