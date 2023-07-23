@extends('admin.layout.master')
@section('title','Trial Balance Report')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>

                <li>Report</li>
                <li>Trial Balance Report</li>
                <li>{{ ($client->company)? $client->company : $client->first_name.' '.$client->last_name}}</li>
                <li class="active">{{$profession->name}}</li>
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

                    <div class="float-right">
                        {{-- <form action="{{route('trial.report')}}" method="get" autocomplete="off">
                            <input type="hidden" name="client_id" value="{{$client->id}}">
                            <input type="hidden" name="profession_id" value="{{$profession->id}}">
                            <input type="hidden" name="date" value="{{$date->format('d/m/Y')}}">

                            <div class="btn-group btn-group">
                                <input name="submit" type="submit" value="Print" class="btn btn-primary">
                                <input name="submit" type="submit" value="Email" class="btn btn-success">
                            </div>
                        </form> --}}
                        <a href="{{request()->fullUrl()."&print=true"}}" class="btn btn-primary">Print</a>
                        <a href="{{request()->fullUrl()."&email=true"}}" class="btn btn-success">Email</a>
                    </div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
@endsection
