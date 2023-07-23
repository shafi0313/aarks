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
                                <label>Select Bussiness Activity</label>
                                <select class="form-control" onchange="location = this.value;">
                                    <option disabled selected >Select Bussiness Activity</option>
                                    @foreach ($client->professions as $profession)
                                    <option value="{{ route('add_card_select_type',$profession->id) }}">{{$profession->name}}</option>
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
