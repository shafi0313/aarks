@extends('admin.layout.master')
@section('title','General Ledger')
@section('content')

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                     <li>
                        <a href="{{ route('general_ledger.index') }}">General Ledger</a>
                    </li>
                    {{-- <li>
                        <a href="#">{{$profession->name}}</a>
                    </li> --}}
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
                                <small>Client Tex Year List</small>
                            </div>
                        <div class="row">
                            <h3>General Ledger Report</h3>

                            <table class="table table-bordered">
                                <tr class="bg-success">
                                    <td colspan="7"><h4 class="text-success">Report for the Activity : {{-- <strong>{{$profession->name}}</strong> --}}</h4></td>
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
                                    <td>{{$client->company}}</td>
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
                        <form action="{{route('general_ledger.report')}}" method="GET">
                            <input type="hidden" name="client_id" value="{{$client->id}}">
                            {{-- <input type="hidden" name="profession_id" value="{{$profession->id}}"> --}}
                            <div class="row" style="border:1px solid blue">
                                <div class="col-md-5">
                                    <div class="row" style="margin: 10px;">
                                        <div class="col-md-4 text-right">
                                            <span>Form Date:</span>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="start_date" class="form-control datepicker" type="text" autocomplete="off">
                                            @if($errors->has('start_date'))
                                                <small class="text-danger">* {{$errors->first()}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row" style="margin: 10px;">
                                        <div class="col-md-4 text-right">
                                            <span class="">Form Account:</span>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-control" name="from_account" id="">
                                                <option>Select Account Code</option>
                                                @foreach($client_account_codes as $client_account_code)
                                                    <option value="{{$client_account_code->code}}">{{$client_account_code->name}} => {{$client_account_code->code}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('from_account'))
                                                <small class="text-danger">* {{$errors->first()}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="row" style="margin: 10px;">
                                        <div class="col-md-4 text-right">
                                            <span>To Date:</span>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="end_date" class="form-control datepicker" type="text" autocomplete="off">
                                            @if($errors->has('end_date'))
                                                <small class="text-danger">* {{$errors->first()}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row" style="margin: 10px;">
                                        <div class="col-md-4 text-right">
                                            <span>To Account:</span>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-control" name="to_account" id="">
                                                <option>Select Account Code</option>
                                                @foreach($client_account_codes as $client_account_code)
                                                    <option value="{{$client_account_code->code}}">{{$client_account_code->name}} => {{$client_account_code->code}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('to_account'))
                                                <small class="text-danger">* {{$errors->first()}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <div style="margin-top: 2%;">
                                        <button type="submit" class="btn btn-primary">Show Report</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->

    <script>
        $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true
        });
    </script>
@endsection
