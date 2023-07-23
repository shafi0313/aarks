@extends('admin.layout.master')
@section('title','Balance Sheet')
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
                <li>Balance Sheet</li>
                <li class="active">{{ $profession->name }}</li>
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
                            <h3>Balance Sheet Report</h3>
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Report for the Activity : <strong>{{$profession->name}}</strong></h3>
                                </div>
                                <div class="panel-body" style="padding:0px;">
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Company /Trust/Partner ship Name</th>
                                                <th>First Name</th>
                                                <th>Middle Name</th>
                                                <th>Last Name</th>
                                                <th>Email Address</th>
                                                <th>ABN Number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{$client->company}} </td>
                                                <td>{{$client->first_name}}</td>
                                                <td>{{$client->last_name}}</td>
                                                <td>{{$client->phone}}</td>
                                                <td>{{$client->email}}</td>
                                                <td>{{$client->abn_number}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <div class="col-md-3"></div>

                            <div class="col-md-5">
                                <div class="row">
                                    <form
                                        action="{{route('balance_sheet.report',['client' => $client->id, 'profession' => $profession->id])}}"
                                        method="GET">
                                        <div class="col-md-4 text-right" style="padding: 10px;">As at</div>
                                        <div class="col-md-4">
                                            <input class="form-control datepicker" name="date" type=""
                                                autocomplete="off" style="padding: 20px;" required
                                                placeholder="DD/MM/YY">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn" style="background-color: orange">Show
                                                Report</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<!-- Script -->
<script>
    $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true
        });
</script>
@stop
