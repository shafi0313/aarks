@extends('admin.layout.master')
@section('title','General Ledger')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>

                <li>
                    <a href="#">Report</a>
                </li>
                <li>
                    <a href="#">General Ledger</a>
                </li>
                <li>
                    <a href="#">{{ ($client->company)? $client->company : $client->first_name.'
                        '.$client->last_name}}</a>
                </li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-lg-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <h2 class="text-center bolder">{{ clientName($client) }}</h2>
                        <h5 class="text-center"><u>Ledger Report From: {{$start_date->format('d/m/Y')}} to :
                                {{$end_date->format('d/m/Y')}}</u></h5>
                    </div>

                    <div class="row">
                        <div class="col-lg-2 pull-right">
                            <form action="{{route('general_ledger.print')}}" method="GET" autocomplete="off">
                                <input type="hidden" name="client_id" value="{{$client->id}}">
                                {{-- <input type="hidden" name="profession_id" value="{{$profession->id}}"> --}}
                                <input name="start_date" hidden value="{{$start_date->format('d/m/Y')}}">
                                <input name="end_date" hidden value="{{$end_date->format('d/m/Y')}}">
                                <input name="from_account" hidden value="{{$from_account}}">
                                <input name="to_account" hidden value="{{$to_account}}">
                                <input name="submit" hidden value="" id="submit">
                                <input type="submit" value="Print" class="btn btn-success" id="print">
                                <input type="submit" value="Email" class="btn btn-info" id="email">
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            @include('admin.reports.general_ledger.table', ['url' => 'general_ledger.transaction'])
                        </div>
                    </div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<script>
    $('#print').click(function(){
        $('#submit').val('print');
    })
    $('#email').click(function(){
        $('#submit').val('email');
    })
</script>
@endsection
