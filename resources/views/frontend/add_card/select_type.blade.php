@extends('frontend.layout.master')
@section('title','Add Card')
@section('content')
<?php $p="acard"; $mp="cf";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group" style="padding:10px;">
                                <label>Add Card</label>
                                <select class="form-control" onchange="location = this.value;">
                                    <option disabled selected value>Select Card Type</option>
                                    <option value="{{ route('customer.show',$profession->id) }}">Customer(Sales/ Invoice)</option>
                                    <option value="{{ route('supplier.show',$profession->id) }}">Supplier(Bill/invoice 4 payment)</option>
                                    <option value="{{ route('add_card_employee',$profession->id) }}">Empolyee (Payroll/Payslip)</option>
                                    <option value="{{ route('personal.show',$profession->id) }}">Personal</option>
                                </select>
                            </div>
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
