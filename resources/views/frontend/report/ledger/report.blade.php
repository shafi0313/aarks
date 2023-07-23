@extends('frontend.layout.master')
@section('title','General Ledger')
@section('content')
<?php $p="gl"; $mp="report";?>
<style>
    .table td,
    .table th {
        padding: 8px;
        line-height: 1;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }
</style>
<section class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-center bolder">
                    {{ $client->fullname}}</h2>
                <h5 class="text-center"><u>Ledger Report From: {{$start_date->format('d/m/Y')}} to :
                        {{$end_date->format('d/m/Y')}}</u></h5>
                <div class="row">
                    <div class="col-lg-3 pull-right">
                        <form action="{{route('ledger.show')}}" method="GET" autocomplete="off">
                            <input type="hidden" name="client_id" value="{{$client->id}}">
                            <input name="start_date" hidden value="{{$start_date->format('d/m/Y')}}">
                            <input name="end_date" hidden value="{{$end_date->format('d/m/Y')}}">
                            <input name="from_account" hidden value="{{$from_account}}">
                            <input name="to_account" hidden value="{{$to_account}}">
                            <input type="submit" name="submit" value="Print" class="btn btn-success">
                            <input type="submit" name="submit" value="Email" class="btn btn-info">
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        @include('admin.reports.general_ledger.table', ['url' => 'ledger.transaction'])
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
