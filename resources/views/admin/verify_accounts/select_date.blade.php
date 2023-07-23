@extends('admin.layout.master')
@section('title','Verify & Fixed Transactions')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Tools</li>
                <li>Verify & Fixed Transactions</li>
                <li class="active">Select Date</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">

            <!-- Settings -->
            {{--            @include('admin.layout.settings')--}}
            <!-- /Settings -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <h3> Report</h3>

                        <table class="table table-bordered">
                            <tr class="bg-success">
                                <td colspan="7">
                                    <h4 class="text-success">Report for the Activity :
                                        <strong>{{$profession->name}}</strong></h4>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Company Name</strong></td>
                                <td><strong>First Name</strong></td>
                                <td><strong>Last Name</strong></td>
                                <td><strong>Email</strong></td>
                                <td><strong>ABN No</strong></td>
                                <td><strong>TFN</strong></td>
                                <td><strong>Date of birth</strong></td>
                            </tr>
                            <tbody>
                                <tr>
                                    <td>{{$client->company_name}}</td>
                                    <td>{{$client->first_name}}</td>
                                    <td>{{$client->last_name}}</td>
                                    <td>{{$client->email}}</td>
                                    <td>{{$client->abn_number}}</td>
                                    <td>{{$client->tfn}}</td>
                                    <td>{{\Carbon\Carbon::parse($client->birthday)->format('d/m/Y')}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <form action="{{route('verify_account.report',[$client->id,$profession->id])}}" method="GET">
                            <div class="col-md-3 text-right" style="padding: 10px;"></div>
                            <div class="col-md-2">
                                <input class="form-control datepicker" name="start_date" type="text" autocomplete="off"
                                    placeholder="DD/MM/YYYY" style="padding: 20px;" required>
                            </div>
                            <div class="col-md-2">
                                <input class="form-control datepicker" name="end_date" type="text" autocomplete="off"
                                    placeholder="DD/MM/YYYY" style="padding: 20px;" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn" style="background-color: orange">Show Report</button>
                            </div>
                        </form>
                    </div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<script>
    $(".datepicker").datepicker({
            dateFormat: date_format,
            changeMonth: true,
            changeYear: true
        });
</script>
@endsection
