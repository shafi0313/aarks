@extends('frontend.layout.master')
@section('title','Enter BILL Item')
@section('content')
<?php $p="eb"; $mp="purchase"?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <form id="store" action="{{route('enter_item.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <strong style="color:green; font-size:20px;">Create Bill Item:
                                    </strong>
                                </div>
                            </div>
                            <hr>
                            <input type="hidden" name="client_id" value="{{$client->id}}">
                            <input type="hidden" name="source" value="CBI">
                            <input type="hidden" name="profession_id" value="{{$profession->id}}">
                            <div class="row">
                                <div class="col-3 form-group">
                                    <label>Supplier Name: <span class="t_red">*</span></label>
                                    <select required class="form-control  form-control-sm" name="customer_card_id">
                                        <option disabled selected value>Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 form-group">
                                    <label>Bill Date: <span class="t_red">*</span> </label>
                                    <input required class="form-control form-control-sm datepicker" type="text"
                                        name="start_date" data-date-format="dd/mm/yyyy">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Bill No: </label>
                                    <input class="form-control form-control-sm" readonly type="text" name="inv_no" id="inv_no" value="{{str_pad(\App\Models\Frontend\Creditor::whereClientId($client->id)->whereProfessionId($profession->id)->max('inv_no')+1,8,'0',STR_PAD_LEFT)}}">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Your Reference: </label>
                                    <input class="form-control form-control-sm" type="text" name="your_ref"
                                        placeholder="Your Reference">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Our Reference: <button type="button" class="btn btn-warning btn-sm"
                                            style="padding:0 13px; font-size:12px" data-toggle="modal"
                                            data-target="#ourReference">
                                            <i class="fas fa-sticky-note"></i></button>
                                    </label>
                                    <input class="form-control form-control-sm ourRefInput" type="text" name="our_ref"
                                        placeholder="Our Reference">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-11 form-group">
                                    <label>Bill terms and Conditions: </label>
                                    <textarea class="form-control" rows="2" placeholder="Bill terms and Conditions"
                                        id="tearms_area"
                                        style="margin-top: 0px;margin-bottom: 0px;height: 145px;resize: none;"
                                        name="quote_terms"></textarea>
                                </div>
                                <div class="col-sm-1">
                                    <br><br>
                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                        data-target="#quote"><i class="fas fa-sticky-note"></i></button>
                                </div>
                            </div>
                            @include('frontend.purchase.item.item')
                            <div class="row justify-content-end">
                                <div class="col-md-5">
                                    <div class="form-group row">
                                        <label for="" class="col-sm-5 col-form-label">Payment
                                            received:</label>
                                        <div class="col">
                                            <select class="form-control" name="bank_account" id="bank_account"
                                                onchange="bankamount()">
                                                <option value="" selected>Select Bank Account</option>
                                                @foreach ($liquid_codes as $liquid_code)
                                                <option value="{{$liquid_code->code}} "> {{$liquid_code->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="" class="col-sm-3 col-form-label">Amount:</label>
                                        <div class="col-sm-8">
                                            <input type="number" disabled min="0" class="form-control"
                                                placeholder="0.00" id="payment_amount" step="any" name="payment_amount">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="" align="right">
                                <input type="button" class="btn btn-info" value="Preview & Save">
                                <input type="submit" class="btn btn-success" value="Save">
                                <input type="button" class="btn btn-secondary" value="E-mail & Save">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer Start -->

<!-- Footer End -->

<!-- Page Content End -->
@include('frontend.purchase.item.modal')

<!-- inline scripts related to this page -->
<script src="{{asset('frontend/assets/js/item.js')}}"></script>
<script>
    $(document).ready(function() {
        readData();
    });

    function bankamount() {
        $("#payment_amount").removeAttr('disabled', 'disabled')
    }
</script>
@stop
