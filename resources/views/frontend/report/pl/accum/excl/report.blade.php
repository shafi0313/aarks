@extends('frontend.layout.master')
@section('title', 'Accumulated P/L GST Exclusive')
@section('content')
<?php $p="accumex"; $mp="advr";?>
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Accumulated P/L GST Exclusive Final Report  <button class="btn btn-success pull-right"  onclick="printDiv('printarea')">Print</button></h5>
                    </div>
                    <div class="card-body"  id="printarea">
                        @include('frontend.report.pl.header')

                        <div align="center" style="padding-top:20px;">
                            <div align="center" style="padding-top:0px;">
                                @include('admin.reports.profit_loss.excl.table')
                                {{-- @include('admin.reports.profit_loss.accum.excl.table') --}}
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
