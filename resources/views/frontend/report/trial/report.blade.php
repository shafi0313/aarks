@extends('frontend.layout.master')
@section('title','Trial Balance')
@section('content')
<?php $p="tb"; $mp="report";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center">
                <!-- PAGE CONTENT BEGINS -->
                <div class="center">
                    <h3 style="padding: 0;margin:0"><b>{{$client->fullname}}</b></h3>
                    <h3 style="padding: 0;margin:0"><b>ABN {{$client->abn_number}}</b></h3>
                    <h4><b>Trial Balance as at: {{$date->format('d/m/Y')}}</b></h4>
                </div>
                <style>
                    .text-danger {
                        color: red;
                    }
                </style>
            </div>
            <div class="col-lg-12">
                <div class="row justify-content-center">
                    @include('admin.reports.trial_balance.table')
                </div>
                <div class="float-right">
                    <form action="{{route('trial.report')}}" method="get" autocomplete="off">
                        <input type="hidden" name="client_id" value="{{$client->id}}">
                        <input type="hidden" name="profession_id" value="{{$profession->id}}">
                        <input type="hidden" name="date" value="{{$date->format('d/m/Y')}}">

                        <div class="btn-group btn-group">
                            <input name="submit" type="submit" value="Print" class="btn btn-primary">
                            <input name="submit" type="submit" value="Email" class="btn btn-success">
                        </div>
                    </form>
                </div>
                <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
@stop
