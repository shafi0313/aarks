@extends('frontend.layout.master')
@section('title','Quote Convert To INV')
@section('content')
<?php $p="qci"; $mp="sales";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading">
                        <h3>Quote Convert To INV</h3>
                    </div>
                    <div class="card-body">
                        <div id="resultshow" style="padding-top:10px;">
                            <div align="center">
                                <h2 style="color:#666666;"><u> {{$dedotrs->first()->customer->name}}</u></h2>
                                <strong>Quote Convert To INV</strong>
                                <style>
                                    table tr td {font-size: 15px; padding: 5px 2px !important}
                                </style>
                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="text-align:center" width="15%">Quote Name</th>
                                            <th style="text-align:center" width="15%">Email</th>
                                            <th style="text-align:center" width="15%">Phone</th>
                                            <th style="text-align:center" width="15%">ABN</th>
                                            <th style="text-align:center">Address</th>
                                        </tr>
                                        <tr>
                                            <td align="center" width="15%"> {{$dedotrs->first()->customer->name}}</td>
                                            <td align="center" width="15%">{{$dedotrs->first()->customer->email}} </td>
                                            <td align="center" width="15%">{{$dedotrs->first()->customer->phone}} </td>
                                            <td align="center" width="15%">{{$dedotrs->first()->customer->abn}} </td>
                                            <td align="center">{{$dedotrs->first()->customer->b_address}}</td>
                                        </tr>
                                    </tbody>
                                </table>


                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="text-align:center">Transaction Date</th>
                                            <th style="text-align:center">Narration</th>
                                            <th style="text-align:center">Code Name</th>
                                            <th style="text-align:center"> Paid Amount</th>
                                            <th style="text-align:center"> Balance</th>
                                        </tr>
                                        @foreach ($dedotrs as $dedotr)
                                        <tr>
                                            <td align="center">{{$dedotr->start_date->format('d/m/Y')}}</td>
                                            <td align="center" class="text-info">Job dec</td>
                                            <td align="center">{{$codes->where('code',$dedotr->chart_id)->first()->name}} </td>
                                            <td align="center">{{number_format($dedotr->payment_amount,2)}}</td>
                                            <td align="center">{{number_format($dedotr->amount,2)}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-center">
                                            <td colspan="4" style="text-align:right"></td>
                                            <td>
                                                <a href="#"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="far fa-edit"></i> Edit
                                                </a>
                                                <a href="{{route('quote.convertStore',[$dedotrs->first()->profession_id, $dedotrs->first()->inv_no])}}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fas fa-exchange-alt"></i> CONVERT
                                                </a>
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
