@extends('frontend.layout.master')
@section('title','General Ledger')
@section('content')
<?php $p="gl"; $mp="report";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading">
                        <h3>General Ledger Select Date</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li class="text-danger" style="list-style: none">{{$error}}</li>
                            @endforeach
                        </ul>

                        @endif
                        <form action="{{route('ledger.show')}}" method="GET" autocomplete="off">
                            <input type="hidden" name="client_id" value="{{$client->id}}">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="row" style="margin: 10px;">
                                        <div class="col-md-4 text-right">
                                            <span>Form Date:</span>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="start_date" class="form-control datepicker" data-date-format="dd/mm/yyyy" required type="text"
                                                autocomplete="off">
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
                                            <select required class="form-control" name="from_account" id="">
                                                <option value disabled selected>Select Account Code</option>
                                                @foreach($codes as $client_account_code)
                                                <option value="{{$client_account_code->code}}">
                                                    {{$client_account_code->name}} =>
                                                    {{$client_account_code->code}}</option>
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
                                            <input name="end_date" class="form-control datepicker" data-date-format="dd/mm/yyyy" required type="text"
                                                autocomplete="off">
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
                                            <select required class="form-control" name="to_account" id="">
                                                <option value disabled selected>Select Account Code</option>
                                                @foreach($codes as $client_account_code)
                                                <option value="{{$client_account_code->code}}">
                                                    {{$client_account_code->name}} =>
                                                    {{$client_account_code->code}}</option>
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
