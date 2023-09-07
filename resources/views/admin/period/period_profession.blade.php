@extends('admin.layout.master')
@section('title','Add/Edit Entry')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('select_method') }}">Add/Edit Entry</a>
                    </li>
                    <li class="bCColor">
                        {{ clientName($client) }}
                    </li>
                    <li class="active">Select Profession</li>
                </ul><!-- /.breadcrumb -->

                <div class="nav-search" id="nav-search">
                    <form class="form-search">
                        <span class="input-icon">
                            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                            <i class="ace-icon fa fa-search nav-search-icon"></i>
                        </span>
                    </form>
                </div><!-- /.nav-search -->
            </div>

            <div class="page-content">

                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">
                            <div class="col-md-3">
                                <h2>Select Profession</h2>
                            </div>
                            <div class="col-md-4" style="padding-top:20px;">
                                <form action="#" name="topform">
                                    <div class="form-group">
                                        <select class="form-control" id="select-client" onchange="location = this.value">
                                            <option> Select a Profession</option>
                                            @foreach ($client->professions as $pro)
                                            <option value="{{route('period_shows',[$client->id,$pro->id])}}"> {{$pro->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->

    <!-- inline scripts related to this page -->
@stop
