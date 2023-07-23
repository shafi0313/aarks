@extends('frontend.layout.master')
@section('title', 'Depreciation Report')
@section('content')
<?php $p="depr"; $mp="advr";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading">
                        <h3>Depreciation Report</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li class="text-danger" style="list-style: none">{{$error}}</li>
                            @endforeach
                        </ul>

                        @endif
                        <form action="" method="get" autocomplete="off">
                            <div class="row justify-content-center">
                                <div class="form-inline col-lg-6">
                                    <label class="mr-2 t_b">Select Bussiness Activity: </label>
                                    <select class="form-control" type="submit" name="profession_id" required onchange="location = this.value;">
                                        <option disabled selected value>Select Profession</option>
                                        @foreach ($client->professions as $profession)
                                        <option value="{{route('cdep_report.date',[$client->id,$profession->id])}}">{{$profession->name}}</option>
                                        @endforeach
                                    </select>
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
