@extends('frontend.layout.master')
@section('title','Comparative Balance Sheet Report')
@section('content')
<?php $p="cbs"; $mp="advr";?>
<div class="container">
    <div class="row">
        <!-- PAGE CONTENT BEGINS -->
        <style>
            .doubleUnderline {
                text-decoration: underline;
                border-bottom: 1px solid #000;
            }

            tr td {
                padding: 2px 5px !important
            }
        </style>

        <div class="col-md-12" style="padding-right:20px; padding-left:20px;">
            <div class="row">
                <div class="col-12">
                    <a href="{{ request()->fullUrl().'&print=true' }}" class="btn btn-info pull-right">Print</a>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-6" align="center">
                    <div style="font-size:24px; font-weight:800;">{{$client->fullname}}<br /></div>
                    <strong style="font-size:14px;">ABN {{$client->abn_number}}</strong><br>
                    {{$client->address}}<br>
                    <strong style="font-size:16px;"><u>Detailed Balance Sheet as at:
                            {{$date->format('d/m/Y')}}</u></strong><br />
                </div>
                <div class="col-md-12" style="padding-top:10px; ">

                    <div class="card-body">
                        @include('admin.reports.comperative_balance_sheet.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
