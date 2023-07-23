@extends('frontend.layout.master')
@section('title','Sales Register')
@section('content')
<?php $p="salreg"; $mp="sales";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card border-secondary">
                    <div class="card-header bg-secondary c_h">
                        <p>Sales Register</p>
                    </div>
                    <div class="card-body">
                        <br>
                        <div class="row justify-content-center">
                            <form action="{{ route('salesRegReport') }}" method="get" autocomplete="off" class="form-inline">
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker" data-date-format="dd/mm/yyyy"
                                        placeholder="From Date" name="from_date">
                                </div>
                                <div class="form-group mx-4">
                                    <input type="text" class="form-control datepicker" data-date-format="dd/mm/yyyy"
                                        placeholder="To Date" name="to_date">
                                </div>
                                <div class="">
                                    <button type="submit" class="btn btn-success">Show Report</button>
                                </div>
                            </form>
                        </div>
                        <br>
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
