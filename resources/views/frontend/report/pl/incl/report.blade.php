@extends('frontend.layout.master')
@section('title', 'Profit & Loss Inclusive Final Report')
@section('content')
<?php $p="pincl"; $mp="report";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2>Profit & Loss Inclusive Final Report <button class="btn btn-success pull-right"
                                onclick="printDiv('printarea')">Print</button></h2>
                    </div>
                    <div class="card-body" id="printarea">
                        @include('frontend.report.pl.header')

                        <div align="center" style="padding-top:20px;">
                            <strong style="font-weight:800; font-size:18px;"><b>Profit and Loss
                                    Statement</b></strong><br />
                            <strong><b><u>For the Period From {{ $from_date }} -
                                        {{ $to_date }}</u></b></strong>
                            <div align="center" style="padding-top:0px;">
                                @include('admin.reports.profit_loss.incl.table')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('admin.reports.profit_loss.print_div')
@stop
