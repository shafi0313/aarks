@extends('admin.layout.master')
@section('title','Comparative Balance Sheet Report')
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Report</li>
                <li>Balance Sheet Report</li>
                <li>{{ $client->fullname }}</li>
                <li class="active">{{ $profession->name }}</li>
            </ul><!-- /.breadcrumb -->

            {{-- <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                            autocomplete="off" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div><!-- /.nav-search --> --}}
        </div>

        <div class="page-content">
            <div align="center" class="row">
                <div class="col-lg-12">

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

                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('comperative_balance_sheet.print',[$client->id,$profession->id,'date'=>$date->format('d/m/Y')])}}" class="btn btn-info pull-right">Print</a>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-6" align="center">
                                <div style="font-size:24px; font-weight:800;">{{$client->fullname}} <br />
                                </div>
                                {{$client->address}}
                                <strong style="font-size:14px;">ABN {{$client->abn_number}}</strong>
                                <br>
                                <strong style="font-size:16px;"><u>Detailed Balance Sheet as at:
                                        {{$date->format('d/m/Y')}}</u></strong>
                                <br />
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
    </div>
</div>

@stop
