@extends('frontend.layout.master')
@section('title', 'Balance Sheet Report')
@section('content')
<?php $p="bs"; $mp="report";?>
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
            <a href="{{route('balance.report',['client_id' => $client->id,'profession_id' => $profession->id,'date'=>$date->format('d/m/Y'), 'print' => true])}}" class="btn btn-info pull-right">Print</a>
            </div>

            <style>
                .doubleUnderline {
                    text-decoration: underline;
                    border-bottom: 1px solid #000;
                }

                .table-bordered td,
                .table-bordered th {
                    border: 1px solid #c7c7c7;
                    padding: 4px;
                    font-size: 15px;
                }
            </style>
            <div class="col-md-6" align="center">
                <div style="font-size:24px; font-weight:800;">{{$client->fullname}} <br /></div>
                <strong style="font-size:14px;">ABN {{$client->abn_number}}</strong><br>
                {{$client->address}}<br>
                <strong style="font-size:16px;"><u>Detailed Balance Sheet as at:
                        {{$date->format('d/m/Y')}}</u></strong><br />
            </div>
            <div class="col-md-12" style="padding-top:10px; ">
                <div class="card">
                    <div class="card-body">
                        @include('admin.reports.balance_sheet.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop
