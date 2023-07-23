@extends('frontend.layout.master')
@section('title','Reconciliation')
@section('content')
<?php $p="brec"; $mp="bank";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading">
                        <h3>Select Profession</h3>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-6">
                                <h3 class="text-success">Select Profession</h3>
                                <select class="form-control" type="submit" name="profession_id"
                                    onchange="location = this.value">
                                    <option disabled selected value>Select Profession</option>
                                    @foreach ($client->professions as $profession)
                                    <option value="{{route('bank_reconciliation.date',[$client->id,$profession->id])}}">
                                        {{$profession->name}}
                                    </option>
                                    @endforeach
                                </select>
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
