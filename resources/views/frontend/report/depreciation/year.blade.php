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
                            <form>
                                <div class="form-group">
                                    <label style="color:green;">Select Year</label>
                                    <select class="form-control" onchange="location = this.value;">
                                        <option>YEAR</option>
                                        @foreach ($periods as $period)
                                        <option value="{{ route('cdep_report.report',[$client->id,$profession->id,$period->year])}}">
                                            {{$period->year}}
                                        </option>
                                        @endforeach
                                    </select>
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
