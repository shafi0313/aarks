@extends('frontend.layout.master')
@section('title','Customer Ledger')
@section('content')
<?php $p="sl"; $mp="purchase";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading">
                        <h3>Supplier Ladger</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li class="text-danger" style="list-style: none">{{$error}}</li>
                            @endforeach
                        </ul>

                        @endif
                        <form action="{{route('sledger.show')}}" method="get" autocomplete="off">
                            <div class="row justify-content-center">
                                <input type="hidden" name="client_id" value="{{$client->id}}">
                                <div class="form-group col-md-5">
                                    <label class="mr-2 t_b">Select Supplier: </label>
                                    <select class="form-control" name="customer_id" required>
                                        <option  selected value>Select</option>
                                        @foreach ($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col">
                                    <label class="mx-2 t_b">From Date:</label>
                                    <input required class="form-control datepicker" data-date-format="dd/mm/yyyy" name="from_date">
                                </div>
                                <div class="form-group col">
                                    <label class="mx-2 t_b">To Date:</label>
                                    <input required class="form-control datepicker" data-date-format="dd/mm/yyyy" name="to_date">
                                </div> &nbsp;&nbsp;&nbsp;&nbsp;
                                <div class="form-group" style="margin-top: 30px ">
                                    <input class="form-control btn btn-info" type="submit" value="Show Report">
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
