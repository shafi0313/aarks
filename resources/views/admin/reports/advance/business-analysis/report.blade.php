@extends('admin.layout.master')
@section('title', 'Monthly Business Analysis Details P & L')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li class="active">Monthly Business Analysis Details P & L</li>
                    <li class="active">Report</li>
                </ul>
            </div>
            <style>
                .table>tbody>tr>td,
                .table>tbody>tr>th,
                .table>tfoot>tr>td,
                .table>tfoot>tr>th,
                .table>thead>tr>td,
                .table>thead>tr>th {
                    padding: 1px 5px;
                    vertical-align: middle;
                }

                .form-control {
                    height: 28px;
                }

                .text-danger {
                    color: red;
                }
            </style>
            <div class="page-content" style="margin-top: 50px;">
                <div class="row">
                    <div class="col-lg-2 text-center pull-right">
                        <a href="javascript:printDiv()" class="btn btn-primary">Print/PDF</a>
                    </div>
                </div>
                <div class="row" id="print-area">
                    <div class="text-center">
                        <h3 style="padding: 0;margin:2px"><b>{{ $client->full_name}}</b></h3>
                        <h4 style="padding: 0;margin:2px"><b>Monthly Business Analysis Details P & L</b></h4>
                        <h4 style="padding: 0;margin:0"><b>ABN {{$client->abn_number}}</b></h4>
                        <h4><b>for the financial year: {{$date->format('d/m/Y')}}</b></h4>
                    </div>
                    @include('admin.reports.advance.business-analysis.monthly-business-analysis-table')
                </div>
            </div>
        </div>
    </div>
    @include('admin.reports.advance.business-analysis.monthly-business-analysis-js')
@endsection
