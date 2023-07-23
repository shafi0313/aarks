@extends('frontend.layout.master')
@section('title','Select Activity')
@section('content')
<?php $p="eb"; $mp="purchase"?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group" style="padding:10px;">
                                <label>Select Bussiness Activity</label>
                                <select class="form-control" type="submit" onchange="location = this.value">
                                    <option disabled selected value>Select Profession</option>
                                    @foreach ($client->professions as $profession)
                                    <option value="{{route('service_bill.enter',$profession->id)}}">{{$profession->name}}</option>
                                    @endforeach
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
