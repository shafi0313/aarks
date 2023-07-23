@extends('frontend.layout.master')
@section('title','Inventory Register')
@section('content')
<?php $p="invReg"; $mp="inventory"?>

    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('invReport')}}" method="get" autocomplete="off">
                                <div class="form-group">
                                    <label>Select Bussiness Activity</label>
                                    <input type="hidden" name="client_id" value="{{$client->id}}">
                                    <select required class="form-control" name="profession_id" >
                                        <option disabled selected value>Select Profession</option>
                                        @foreach ($client->professions as $profession)
                                        <option value="{{$profession->id}}">{{$profession->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="">From Date</label>
                                        <input required class="form-control datepicker" data-date-format="dd/mm/yyyy" type="text" name="from">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for=""> To Date</label>
                                        <input required class="form-control datepicker" data-date-format="dd/mm/yyyy" type="text" name="to">
                                    </div>
                                </div>
                                <button class="btn btn-success btn-block btn-sm" type="submit">Show Report</button>
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
