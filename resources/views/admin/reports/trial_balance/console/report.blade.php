@extends('admin.layout.master')
@section('title','Console Trial Balance')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>

                <li>Reports</li>
                <li>Console Trial Balance</li>
                <li class="active">{{ ($client->company)? $client->company : $client->first_name.' '.$client->last_name}}</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content" style="float: ">
            <div align="center" class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row center">
                        <h2 style="padding: 0;margin:0"><b>{{ ($client->company)? $client->company : $client->first_name.' '.$client->last_name}}</b></h2>
                        <h2 style="padding: 0;margin:0"><b>ABN {{$client->abn_number}}</b></h2>
                        <h4><b>Trial Balance as at: {{\Carbon\Carbon::parse($date)->format('d/m/Y')}}</b></h4>
                    </div>
                    <style>
                        .text-danger {
                            color: red;
                        }
                    </style>
                    <div class="row">
                        @include('admin.reports.trial_balance.table')
                    </div>

                    <div class="row text-right">
                        <div class="btn-group btn-group">
                            <a href="{{request()->fullUrl()."&print=true"}}" class="btn btn-primary">Print</a>
                            <a href="{{request()->fullUrl()."&email=true"}}" class="btn btn-success">Email</a>
                        </div>
                    </div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
@endsection
