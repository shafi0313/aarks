@extends('frontend.layout.master')
@section('title','Debtors Report')
@section('content')
@php $p="debrep"; $mp="sales"; @endphp
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-heading">
                        <h3>Debtors Report</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('debtors_report.report')}}" method="get" autocomplete="off"
                            class="form-inline">
                            <input type="hidden" name="client_id" value="{{$client->id}}">
                            <div class="row justify-content-center">
                                <div class="form-inline">
                                    <label class="mx-2 t_b">Date,as at:</label>
                                    <input class="form-control datepicker" data-date-format="dd/mm/yyyy" name="end_date">
                                </div>
                                <div align="center">
                                    <button class="btn btn-primary ml-2" type="submit">Show Report</button>
                                    <button class="btn btn-success" type="submit">Send Email</button>
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
