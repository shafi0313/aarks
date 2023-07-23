@extends('admin.layout.master')
@section('title','GST/BAS(Consol.Cash)')
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
                    <li>GST REPORT/BAS(CASH)</li>
                    <li class="active">{{ $client->fullname }}</li>
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
                <div class="col-lg-12">
                    <!-- PAGE CONTENT BEGINS -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="glyphicon glyphicon-align-justify"></i> Clients Detail : {{$client->fullname}}
                {{-- <strong class="pull-right"><a class="back" href=""><i
                class="glyphicon glyphicon-chevron-left"></i> Back</a></strong> --}}
                </h3>
            </div>
            <div class="panel-body" style="padding:0px;">
                <table class="table table-bordered table-striped">
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
            </div>
            <div align="center">
                <h2>GST REPORT/BAS(CASH)</h2>
                <h4><u> From Date : {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y')}} To {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y')}}</u></h4>
            </div>
            @include('admin.reports.cash_basis.table')
        </div>
    </div>
</div>


                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<!-- inline scripts related to this page -->

@endsection
