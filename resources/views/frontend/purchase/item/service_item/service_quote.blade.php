@extends('frontend.layout.master')
@section('title','Service Item')
@section('content')
<?php $p="so"; $mp="purchase"?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <form id="store" action="{{route('service_item.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <strong style="color:green; font-size:20px;">Create Service Item:
                                    </strong>
                                </div>
                            </div>
                            <hr>
                            <input type="hidden" name="client_id" value="{{$client->id}}">
                            <input type="hidden" name="source" value="PIV">
                            <input type="hidden" name="profession_id" value="{{$profession->id}}">
                            <div class="row">
                                <div class="col-2 form-group">
                                    <label>Supplier Name:<span class="t_red">*</span></label>
                                    <select required class="form-control  form-control-sm" name="customer_card_id">
                                        <option disabled selected value>Select Customer</option>
                                        @foreach ($suppliers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 form-group">
                                    <label>Order Date:<span class="t_red">*</span></label>
                                    <input required class="form-control form-control-sm datepicker" type="text"
                                        name="start_date" data-date-format="dd/mm/yyyy">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Order Expiry Date:<span class="t_red">*</span> </label>
                                    <input class="form-control form-control-sm datepicker" type="text"
                                        name="end_date" data-date-format="dd/mm/yyyy">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Order No: </label>
                                    <input class="form-control form-control-sm" readonly type="text" name="inv_no" id="inv_no"
                                        value="{{str_pad(\App\Models\Frontend\CreditorServiceOrder::whereClientId($client->id)->whereProfessionId($profession->id)->max('inv_no')+1,8,'0',STR_PAD_LEFT)}}">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Your Reference: </label>
                                    <input class="form-control form-control-sm" type="text" name="your_ref"
                                        placeholder="Your Reference">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Our Reference:
                                        <button type="button" class="btn btn-warning btn-sm" style="padding:0 13px; font-size:12px" data-toggle="modal"
                                            data-target="#ourReference">
                                            <i class="fas fa-sticky-note"></i>
                                        </button>
                                    </label>
                                    <input class="form-control form-control-sm ourRefInput" type="text" name=""
                                        placeholder="Our Reference">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-11 form-group">
                                    <label>Order terms and Conditions: </label>
                                    <textarea class="form-control" rows="2" placeholder="Order terms and Conditions"
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
                            <div class="" align="right">
                                {{-- <input type="button" class="btn btn-info" value="Preview & Save"> --}}
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
        $('#example').DataTable( {
            "lengthMenu": [[50, 100, -1], [50, 100, "All"]],
            "order": [[ 0, "asc" ]]
        });
        readData();
    });
</script>
@stop
