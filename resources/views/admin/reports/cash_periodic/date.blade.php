@extends('admin.layout.master')
@section('title', 'Cash Periodic GST/BAS')
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
                    <li>Periodic BAS(s/actv.Cash)</li>
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

                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="glyphicon glyphicon-align-justify"></i> Clients
                                            Detail </h3>
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
                                                <td>{{ $client->company }}</td>
                                                <td>{{ $client->first_name }}</td>
                                                <td>{{ $client->last_name }}</td>
                                                <td>{{ $client->email }}</td>
                                                <td>{{ $client->abn_number }}</td>
                                                <td>{{ $client->tax_file_number }}</td>
                                                <td>{{ $client->birthday }}</td>
                                                <td>{{ $client->phone }}</td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="glyphicon glyphicon-align-justify"></i> GST BAS
                                        </h3>
                                    </div>
                                    <div class="panel-body" style="padding:0px;">
                                        <form method="get"
                                            action="{{ route('cash_periodic.report', [$client->id, $profession->id]) }} ">
                                            <input type="hidden" name="profession_id" value="{{ $profession->id }} " />
                                            <input type="hidden" name="client_id" value="{{ $client->id }}" />
                                            <table class="table table-bordered table-hover">
                                                <tr>
                                                    <th>Period Date to Date</th>
                                                </tr>
                                                @foreach ($periods as $period)
                                                    <tr>
                                                        <td>
                                                            {{ $period->first()->year }} =>

                                                            @foreach ($period as $per)
                                                                <div class="checkbox-inline px-4">
                                                                    <label>
                                                                        <input type="checkbox" name="peroid_id[]"
                                                                            value="{{ $per->id }} ">{{ $per->start_date->format(aarks('frontend_date_format')) }}
                                                                        To
                                                                        {{ $per->end_date->format(aarks('frontend_date_format')) }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td>
                                                        <button class="submitbutton btn btn-info form-control"
                                                            type="submit">Period Report</button>
                                                    </td>
                                                </tr>
                                            </table>
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


    <script>
        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true
        });
    </script>

@endsection
