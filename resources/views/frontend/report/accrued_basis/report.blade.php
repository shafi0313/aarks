@extends('frontend.layout.master')
@section('title','GST/BAS(Accrude Basis)')
@section('content')
<?php $p="abasis"; $mp="report";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    {{-- <div class="card-heading">
                        <p>Accrued Basis Report</p>
                    </div> --}}
                    <style>
                        table tr {
                            font-size: 14px;
                        }
                    </style>
                    <div class="card-body">
                        <div class="panel panel-primary">
                            <div class="card-heading">
                                <p class="panel-title"><i class="glyphicon glyphicon-align-justify"></i> Clients Detail:
                                    @if(empty($client->company)) {{$client->first_name.' '.$client->last_name}} @else
                                    {{$client->company}}
                                    @endif
                                </p>
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
                                        <th>Date Of Birth</th>
                                        <th>Phone Number</th>
                                    </tr>
                                    <tr>
                                        <td>{{$client->company}}</td>
                                        <td>{{$client->first_name}}</td>
                                        <td>{{$client->last_name}}</td>
                                        <td>{{$client->email}}</td>
                                        <td>{{$client->abn_number}}</td>
                                        <td>{{$client->tax_file_number}}</td>
                                        <td>{{$client->birthday}}</td>
                                        <td>{{$client->phone}}</td>
                                    </tr>
                                </table>
                            </div><br>
                            <div align="center">
                                <h3>GST REPORT/BAS(Accrued)</h3>
                                <h5><u> From Date : {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y')}} To
                                        {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y')}}</u></h5>
                            </div><br>

                            {{-- @include('admin.reports.accrued_basis.table') --}}
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
