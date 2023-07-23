@extends('frontend.layout.master')
@section('title','Supplier Convert To INV')
@section('content')
<?php $p="socb"; $mp="purchase"?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading">
                        <h3>Supplier Convert To INV</h3>
                    </div>
                    <div class="card-body">
                        <div id="resultshow" style="padding-top:10px;">
                            <div align="center">
                                <h2 style="color:#666666;"><u> {{$creditors->first()->customer->name}}</u></h2>
                                <strong>Supplier Convert To INV</strong>
                                <style>
                                    table tr td {font-size: 15px; padding: 5px 2px !important}
                                </style>
                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="text-align:center" width="15%">Supplier Name</th>
                                            <th style="text-align:center" width="15%">Email</th>
                                            <th style="text-align:center" width="15%">Phone</th>
                                            <th style="text-align:center" width="15%">ABN</th>
                                            <th style="text-align:center">Address</th>
                                        </tr>
                                        <tr>
                                            <td align="center" width="15%"> {{$creditors->first()->customer->name}}</td>
                                            <td align="center" width="15%">{{$creditors->first()->customer->email}} </td>
                                            <td align="center" width="15%">{{$creditors->first()->customer->phone}} </td>
                                            <td align="center" width="15%">{{$creditors->first()->customer->abn}} </td>
                                            <td align="center">{{$creditors->first()->customer->b_address}}</td>
                                        </tr>
                                    </tbody>
                                </table>


                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="text-align:center">Transaction Date</th>
                                            <th style="text-align:center">Naration</th>
                                            <th style="text-align:center">Code Name</th>
                                            <th style="text-align:center"> Paid Amount</th>
                                            <th style="text-align:center"> Balance</th>
                                        </tr>
                                        @php
                                            $payment_amount = 0;
                                            $balance = $creditors->first()->customer->opening_blnc;
                                        @endphp
                                        @foreach ($creditors as $creditor)
                                        <tr>
                                            <td align="center">{{$creditor->start_date->format('d/m/Y')}}</td>
                                            <td align="center" class="text-info">
                                                INV#{{$creditor->inv_no}}
                                            </td>
                                            <td align="center">{{$codes->where('code',$creditor->chart_id)->first()->name}} </td>
                                            <td align="center">{{number_format($creditor->payment_amount,2)}}</td>
                                            <td align="center">{{number_format($creditor->amount,2)}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" style="text-align:right"></td>
                                            <td class="text-center d-flex">
                                                <a href="{{route('service_item.edit',$creditors->first()->inv_no)}}"
                                                    class="btn btn-warning btn-sm mx-1">
                                                    <i class="far fa-edit"></i> Edit
                                                </a>
                                                <form action="{{route('service_item.convertStore',[$creditors->first()->client_id,$creditors->first()->profession_id,$creditors->first()->inv_no,])}}" method="post">
                                                    @csrf
                                                    <button type="submit" class="btn btn-info btn-sm">
                                                    <i class="fas fa-truck-moving fa-lg"></i> CONVERT
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
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
