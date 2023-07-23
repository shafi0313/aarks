@extends('frontend.layout.master')
{{-- @section('title','GST/BAS(Cash Basis') --}}
@section('content')
<?php $p="cbasis"; $mp="report";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading">
                        <h3>Cash Basis Select Date</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li class="text-danger" style="list-style: none">{{$error}}</li>
                            @endforeach
                        </ul>

                        @endif
                        <form action="{{route('cbasis.report')}}" method="get" autocomplete="off">
                            <div class="row justify-content-center">
                                <input type="hidden" name="client_id" value="{{$client->id}}">
                                <div class="form-inline col-lg-4">
                                    <label class="mr-2 t_b">Select Bussiness Activity: </label>
                                    <select class="form-control" type="submit" name="profession_id" required>
                                        <option disabled selected value>Select Customer</option>
                                        @foreach ($client->professions as $profession)
                                        <option value="{{$profession->id}}">{{$profession->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-inline col-lg-3">
                                    <label class="mx-2 t_b">From Date:</label>
                                    <input required class="form-control datepicker" data-date-format="dd/mm/yyyy" name="from_date">
                                </div>
                                <div class="form-inline col-lg-3">
                                    <label class="mx-2 t_b">To Date:</label>
                                    <input required class="form-control datepicker" data-date-format="dd/mm/yyyy" name="to_date">
                                </div> &nbsp;&nbsp;&nbsp;&nbsp;
                                <div class="form-inline mt-3">
                                    <input class="form-control btn btn-info" type="submit" value="Show Report">
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
