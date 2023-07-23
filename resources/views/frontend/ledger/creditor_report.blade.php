@extends('frontend.layout.master')
@section('title','Dashboard')
@section('content')
@php $p="sl"; $mp="purchase"; @endphp
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-heading">
                            <h3>Creditor Report</h3>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-3 form-group">
                                    <label>Form Date</label>
                                    <input class="form-control" type="date" name="">
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Form Date</label>
                                    <input class="form-control" type="date" name="">
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <button class="btn btn-primary" type="submit">Show Report</button>
                                </div>
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

