@extends('frontend.layout.master')
@section('title', 'Complete Balance Sheet Report')
@section('content')
<?php $p="cfr"; $mp="advr";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading">
                        <h3>Complete Balance Sheet Report Select Date</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li class="text-danger" style="list-style: none">{{$error}}</li>
                            @endforeach
                        </ul>

                        @endif
                        <form action="{{route('cr_complete_financial.select_report')}}" method="get" autocomplete="off">
                            <div class="row justify-content-center">
                                <input type="hidden" name="client_id" value="{{$client->id}}">
                                <div class="form-inline">
                                    <label class="mr-2 t_b">Select Bussiness Activity: </label>
                                    <select required class="form-control" type="submit" name="profession_id">
                                        <option disabled selected value>Select Profession</option>
                                        @foreach ($client->professions as $profession)
                                        <option value="{{$profession->id}}">{{$profession->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-inline ml-3">
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
