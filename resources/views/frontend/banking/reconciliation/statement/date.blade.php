@extends('frontend.layout.master')
@section('title','Reconciliation Statement')
@section('content')
<?php $p="brecs"; $mp="bank";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading">
                        <h3>Reconciliation statement Select Date</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li class="text-danger" style="list-style: none">{{$error}}</li>
                            @endforeach
                        </ul>

                        @endif
                        <form action="{{route('bank_recon_statement.report',[$client->id,$profession->id])}}" method="GET" autocomplete="off">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="row" style="margin: 10px;">
                                        <div class="col-md-4 text-right">
                                            <span>Upto date transaction: </span>
                                        </div>
                                        <div class="col-md-5">
                                            <input name="end_date" class="form-control datepicker" value="{{old('end_date')}}"
                                                data-date-format="dd/mm/yyyy" required type="text" autocomplete="off">
                                            @if($errors->has('end_date'))
                                            <small class="text-danger">* {{$errors->first()}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row" style="margin: 10px;">
                                        <div class="col-md-4 text-right">
                                            <span class="">Bank account name:</span>
                                        </div>
                                        <div class="col-md-5">
                                            <select required class="form-control" name="account" id="">
                                                <option value disabled selected>Select Account Code</option>
                                                @foreach($codes as $client_account_code)
                                                <option {{old('account') == $client_account_code->code ? 'selected': '' }} value="{{$client_account_code->code}}">
                                                    {{$client_account_code->name}} =>
                                                    {{$client_account_code->code}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('account'))
                                            <small class="text-danger">* {{$errors->first()}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-md-4">
                                            <br>
                                            <div style="margin-top: 2%;">
                                                <button type="submit" class="btn btn-primary">Show Report</button>
                                            </div>
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
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->

@stop
