@extends('frontend.layout.master')
@section('title','GST/BAS(Cash Basis)')
@section('content')
<?php $p="cbasis"; $mp="report";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                  {{--   <div class="card-heading">
                        <h2>Cash Basis Report</h2>
                    </div> --}}
                    <style>
                        table tr {
                            font-size: 14px;
                        }
                    </style>
                    <div class="card-body">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="glyphicon glyphicon-align-justify"></i> Clients Detail
                                    :
                                    @if(empty($client->company)) {{$client->first_name.' '.$client->last_name}} @else
                                    {{$client->company}}
                                    @endif
                                    {{-- <strong class="pull-right"><a class="back" href=""><i
                        class="glyphicon glyphicon-chevron-left"></i> Back</a></strong> --}}
                                </h3>
                            </div>
                            <div class="panel-body" style="padding:0px;">
                                <table class="table table-bordered table-striped table-sm">
                                    <tr>
                                        <th>Company Name</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>ABN No</th>
                                        <th>TFN</th>
                                        {{-- <th>Date Of Birth</th> --}}
                                        <th>Phone Number</th>
                                    </tr>
                                    <tr>
                                        <td>{{$client->company}}</td>
                                        <td>{{$client->first_name}}</td>
                                        <td>{{$client->last_name}}</td>
                                        <td>{{$client->email}}</td>
                                        <td>{{$client->abn_number}}</td>
                                        <td>{{$client->tax_file_number}}</td>
                                        {{-- <td>{{$client->birthday}}</td> --}}
                                        <td>{{$client->phone}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div align="center">
                                <h2>GST REPORT/BAS(CASH)</h2>
                                <h4><u> From Date : {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y')}} To
                                        {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y')}}</u></h4>
                            </div><br>
                            @include('admin.reports.cash_basis.table')
                        </div>
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
