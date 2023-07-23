@extends('frontend.layout.master')
@section('title','Inventory Register')
@section('content')
<?php $p="invReg"; $mp="inventory"?>

<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div align="center"><strong style="padding:50px; font-size:22px;"><span class="pull-left"
                                    style="color:#800000;">Activity : {{$registers->first()->profession->name}}</span>
                                &nbsp;&nbsp; <span class="pull-right" style="color:#0606cc;">Date : {{$start}} To
                                    {{$end}}</span></strong></div>
                        @php
                        $closeQty = $closeRate = $closeAmount = 0;
                        @endphp
                        @foreach($registers->groupBy('item_name') as $regs)
                        <strong
                            style="color:#00cc00;">{{ucfirst(join(' ', explode('-',$regs->first()->item_name )))}}</strong>
                        <table class="table-bordered" style="width:100%">
                            <thead>
                                <tr style="vertical-align:top;">
                                    <th rowspan="2"
                                        style="text-align:center;border-right: 3px solid red; font-size:14px;"
                                        width="10%">Date</th>
                                    <th style="text-align:center; border-right: 3px solid red; font-size:14px;"
                                        colspan="3" width="30%">Purchase
                                    </th>
                                    <th style="text-align:center; border-right: 3px solid red; font-size:14px;"
                                        colspan="3" width="30%">Sales
                                    </th>
                                    <th style="text-align:center; border-right: 3px solid red; font-size:14px;"
                                        colspan="3" width="30%">Closing
                                    </th>
                                </tr>
                                <tr>
                                    <th style="text-align:center; font-size:12px;">QTY</th>
                                    <th style="text-align:center;font-size:12px;">Rate</th>
                                    <th style="text-align:center;font-size:12px; border-right: 3px solid red;">Value
                                    </th>
                                    <th style="text-align:center;font-size:12px;">QTY</th>
                                    <th style="text-align:center;font-size:12px;">Rate</th>
                                    <th style="text-align:center;font-size:12px; border-right: 3px solid red;">Value
                                    </th>
                                    <th style="text-align:center;font-size:12px;">QTY</th>
                                    <th style="text-align:center;font-size:12px;">Rate</th>
                                    <th style="text-align:center;font-size:12px; border-right: 3px solid red;">Value
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($regs as $reg)
                                <tr>
                                    <td style="text-align:center;border-right: 3px solid red;" width="10%">
                                        {{$reg->date->format('d/m/Y')}}</td>
                                    <td style="text-align:center;">{{number_format($reg->purchase_qty,2)}}</td>
                                    <td style="text-align:center;">{{number_format($reg->purchase_rate,2)}}</td>
                                    <td style="text-align:center;border-right: 3px solid red;">
                                        {{number_format($reg->purchase_qty * $reg->purchase_rate,2)}}</td>

                                    <td style="text-align:center;">{{number_format($reg->sales_qty,2)}}</td>
                                    <td style="text-align:center;">{{number_format($reg->sales_rate,2)}}</td>
                                    <td style="text-align:center;border-right: 3px solid red;">
                                        {{number_format($reg->sales_qty * $reg->sales_rate,2)}}</td>

@php
if($reg->source == 'stock'){
$closeQty    = $reg->close_qty;
$closeRate   = $reg->close_rate;
$closeAmount = $reg->close_amount;
}elseif($reg->source == 'purchase'){
$closeQty    += $reg->purchase_qty;
$closeAmount += $reg->purchase_qty * $reg->purchase_rate ;
$closeRate   = $closeAmount / $closeQty;
}elseif($reg->source == 'sales'){
$closeQty    -= $reg->sales_qty;
$closeAmount = $closeRate * $closeQty ;
}
@endphp
                                    <td style="text-align:center;">{{number_format($closeQty,2)}}</td>
                                    <td style="text-align:center;">{{number_format($closeRate,2)}}</td>
                                    <td style="text-align:center;border-right: 3px solid red;">
                                        {{number_format($closeAmount,2)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endforeach
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
