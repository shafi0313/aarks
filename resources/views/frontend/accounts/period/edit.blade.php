@extends('frontend.layout.master')
@section('title','Add Edit Period')
@section('content')
<?php $p="aep"; $mp="acccounts";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary">
                    <div class="card-heading">
                        <h3 class="card-title">Activity Name: {{$profession->name}}
                            <span style="padding-left:calc(100%/2); color:hotpink;">Period:
                                {{ $period->start_date->format(aarks('frontend_date_format'))}} to
                                {{ $period->end_date->format(aarks('frontend_date_format'))}}
                            </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <h3 style="color:green;">Select Account Name: </h3>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <select class="form-control chosen-select select2Single" id="subid" name="subid"
                                        onchange="location = this.value">
                                        <option>
                                            <p>Select Account Name</p>
                                        </option>
                                        @foreach ($client->account_codes as $client_ac_code)
                                        @if ($client_ac_code->type ==1)
                                        <option
                                            value="{{route('client.periodCodeAddEdit',[$client_ac_code->id,$client_ac_code->code,$client->id,$profession->id,$period->id])}}"
                                            style="color: green;">
                                            {{$client_ac_code->name}}
                                        </option>
                                        @else
                                        <option
                                            value="{{route('client.periodCodeAddEdit',[$client_ac_code->id,$client_ac_code->code,$client->id,$profession->id,$period->id])}}"
                                            style="color: hotpink;">
                                            {{$client_ac_code->name}}
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br><br><br><br><br><br><br>
                        @include('frontend.accounts.period.payg')
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
