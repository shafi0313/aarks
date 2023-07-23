@extends('frontend.layout.master')
@section('title','Manage Deduction')
@section('content')
<?php $p="md"; $mp="payroll";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-heading d-flex">
                            <p>Deduction</p>
                            {{-- <button type="button" class="btn m_c_b ml-auto" data-toggle="modal"
                                data-target="#addDeducsModal">
                                Add Deduction Category
                            </button> --}}
                        </div>
                        <form role="form" id="deductionForm"
                            action="{{route('clientdeduction.update',$clientdeduction->id)}} " method="POST">
                            @csrf @method('put')
                            <input type="hidden" name="client_id" value="{{client()->id}} ">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <label for="" class="control-label">Deduction
                                                Name<strong style="color:red;">*</strong></label>
                                            <div class="col-10">
                                                <input type="text" class="form-control form-control-sm" name="name"
                                                    id="name" value="{{$clientdeduction->name}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group row ">
                                            <label for="rate" class="col-sm-4 control-label text-right">Equals</label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control form-control-sm" id="rate"
                                                    name="rate" value="{{$clientdeduction->rate}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group row ">
                                            <label for="tools" class="col-sm-4 control-label text-right">Percent
                                                of</label>
                                            <div class="col-sm-8">
                                                <select name="tools" id="tools" class="form-control form-control-sm">
                                                    <option {{$clientdeduction->period == 'Pay Period'?'selected':''}}
                                                        value="Pay Period" selected>Pay Period</option>
                                                    <option {{$clientdeduction->period == 'Per Hours'?'selected':''}}
                                                        value="Per Hours">Per Hours</option>
                                                    <option {{$clientdeduction->period == 'Per Month'?'selected':''}}
                                                        value="Per Month">Per Month</option>
                                                    <option
                                                        {{$clientdeduction->period == 'Per Forthnighty'?'selected':''}}
                                                        value="Per Forthnighty">Per Forthnighty
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group row">
                                            <label for="fix_amt"
                                                class="col-sm-4 control-label text-right">Equals</label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control form-control-sm" id="fix_amt"
                                                    name="fix_amt" value="{{$clientdeduction->fix_amt}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group row">
                                            <label for="period" class="col-sm-4 control-label text-right">Dollars
                                                Per</label>
                                            <div class="col-sm-8">
                                                <select name="period" id="period" class="form-control form-control-sm">
                                                    <option {{$clientdeduction->period == 'Pay Period'?'selected':''}}
                                                        value="Pay Period" selected>Pay Period</option>
                                                    <option {{$clientdeduction->period == 'Per Hours'?'selected':''}}
                                                        value="Per Hours">Per Hours</option>
                                                    <option {{$clientdeduction->period == 'Per Month'?'selected':''}}
                                                        value="Per Month">Per Month</option>
                                                    <option
                                                        {{$clientdeduction->period == 'Per Forthnighty'?'selected':''}}
                                                        value="Per Forthnighty">Per Forthnighty
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{-- <div class="form-group row">
                        <label for="exampleInputEmail1" class="col-sm-4 control-label">Deducation
                            Name<strong style="color:red;">*</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="name" id="name" value="{{$clientdeduction->name}}">
                            </div>

                    </div> --}}

                    {{-- <div class="form-group row">
                        <label for="rate" class="col-sm-4 control-label">Equals</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="rate" name="rate" value="{{$clientdeduction->rate}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="tools" class="col-sm-4 control-label">Percent of</label>
                <div class="col-sm-8">
                    <select name="tools" id="tools" class="form-control">
                        <option value="BS" {{$clientdeduction->tools == 'BS'?'selected':''}}>Basic Sallary</option>
                        <option value="GH" {{$clientdeduction->tools == 'GH'?'selected':''}}>Gross Hours</option>
                    </select>
                </div>
            </div> --}}
            {{-- <div class="form-group row">
                        <label for="fix_amt" class="col-sm-4 control-label">Equals</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="fix_amt" name="fix_amt" value="{{$clientdeduction->fix_amt}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="period" class="col-sm-4 control-label">Dollars Per</label>
        <div class="col-sm-8">
            <select name="period" id="period" class="form-control">
                <option {{$clientdeduction->period == 'Pay Period'?'selected':''}} value="Pay Period" selected>Pay
                    Period</option>
                <option {{$clientdeduction->period == 'Per Hours'?'selected':''}} value="Per Hours">Per Hours</option>
                <option {{$clientdeduction->period == 'Per Month'?'selected':''}} value="Per Month">Per Month</option>
                <option {{$clientdeduction->period == 'Per Forthnighty'?'selected':''}} value="Per Forthnighty">Per
                    Forthnighty
                </option>
            </select>
        </div>
    </div> --}}

    <div class="row" style="padding:0  20px 10px 50px">
        <div class="col-md-2">
            <div class="form-group">
                <div class="radio">
                    <label>
                        <input type="radio" name="limit" id="nolimit" value="1"
                            {{$clientdeduction->limit == 1?'checked':''}}>
                        No Limit
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <div class="radio">
                    <label>
                        <input type="radio" name="limit" id="limit" value="2"
                            {{$clientdeduction->limit == 2?'checked':''}}>
                        Until Date
                    </label>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal-footer">
        <a href="{{route('clientdeduction.index')}}" class="btn btn-danger">Close</a>
        <button type="submit" class="btn btn-info">Submit</button>
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
