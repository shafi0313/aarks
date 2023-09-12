@extends('admin.layout.master')
@section('title','Console Balance Sheet Report')
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
                <li>
                    Console Balance Sheet
                </li>
                <li>{{ clientName($client) }}</li>
            </ul><!-- /.breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                            autocomplete="off" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div><!-- /.nav-search -->
        </div>

        <div class="page-content">
            <div align="center" class="row">
                <div class="col-xs-12">
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
                    <form action="" target="_blank" method="">
                        <div class="col-md-12" style="padding-right:20px; padding-left:20px;">
                            <div class="col-xs-12">
                                <div class="col-md-3"></div>
                                <div class="col-md-6" align="center">
                                    <h2 style="padding: 0;margin:0"><b>{{ clientName($client)}}</b></h2>
                                    <h2 style="padding: 0;margin:0"><b>ABN {{$client->abn_number}}</b></h2>
                                    <br>
                                    <strong style="font-size:16px;"><u>Detailed Balance Sheet as at:
                                            {{$date->format('d/m/Y')}}</u></strong>
                                    <br />
                                </div>
                                <div class="col-md-12" style="padding-top:10px; ">

                                    <div class="panel-body">
                                        @include('admin.reports.balance_sheet.table')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
