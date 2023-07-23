@extends('frontend.layout.master')
@section('title','Card List')
@section('content')
<?php $p="cl"; $mp="cf";?>
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
                                    <option value="{{ route('customer.view',$profession) }}">Customer(Sales/ Invoice)</option>
                                    <option value="{{ route('supplier.view',$profession) }}">Supplier(Bill/invoice 4 payment)</option>
                                    <option value="{{ route('employee.view',$profession) }}">Empolyee (Payroll/Payslip)</option>
                                    <option value="{{ route('personal.view',$profession) }}">Personal</option>
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
