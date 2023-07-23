@extends('frontend.layout.master')
@section('title','Profession')
@section('content')
<?php $p="cdep"; $mp="acccounts"?>

<div class="page-content">
    <div class="row">
        <div class="col-lg-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row justify-content-center">
                <div class="col-md-6" style="padding:20px; border:1px solid #999999;">
                    <form action="" method="">
                        <div class="form-group">
                            <label style="color:green;">Select Business Activity</label>
                            <select class="form-control" onchange="location = this.value;">
                                <option value="">Activity Balance Sheet Report</option>
                                @foreach ($client->professions as $profession)
                                <option value="{{ route('client.dep_group.index',[$client->id,$profession->id])}}">
                                    {{$profession->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>
@stop
