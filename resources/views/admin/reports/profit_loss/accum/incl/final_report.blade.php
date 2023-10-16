@extends('admin.layout.master')
@section('title','Console Accumulated P/L GST Inclusive')
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Reports</li>
                <li class="active">Console Accumulated P/L GST Inclusive</li>
                <li class="active">Balance</li>
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
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <div class="col-xs-12">
                            <div align="center" id="printarea">
                                @include('admin.reports.profit_loss.header')
                                <div align="center" style="padding-top:20px;">
                                    <strong style="font-weight:800; font-size:18px;">{{$client->full_name}}
                                    </strong>
                                    <br />
                                    <strong>ABN : {{$client->abn_number}}</strong><br />
                                    <span> {{$client->street_address}}, {{$client->suburb}},
                                        {{$client->state}}</span><br>
                                    <strong style="font-weight:800; font-size:18px;"><b>Profit and Loss
                                            Statement</b></strong><br />
                                    <strong><b><u>For the Period From {{$from_date}} - {{$to_date}}</u></b></strong>

                                    <div align="center" style="padding-top:0px;">
                                        @include('admin.reports.profit_loss.incl.table')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-right">
                        <div style="max-width: 1327px; margin: 0 auto">
                            <button onclick="printDiv('printarea')" class="btn btn-primary">Print</button>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

@include('admin.reports.profit_loss.print_div')
@endsection
