@extends('frontend.layout.master')
@section('title','BS Input')
@section('content')
<?php $p="in"; $mp="bank";?>

    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group" style="padding:10px;">
                                <label>Select Bussiness Activity</label>
                                <select class="form-control" onchange="location = this.value;">
                                    <option disabled selected value>Select Bussiness Activity</option>
                                    @foreach ($client->professions as $profession)
                                        <option value="{{ route('cbs_input.inputbs',[$client->id,$profession->id]) }}">{{$profession->name}}</option>
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
