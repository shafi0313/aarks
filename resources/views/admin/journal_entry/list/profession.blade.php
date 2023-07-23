@extends('admin.layout.master')
@section('title','Profession')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Add/Edit Data</li>
                <li>Journal Entry</li>
                <li><a href="{{ route('journal_entry_client') }}">Client List</a></li>
                <li class="active">Select Business Activity</li>
            </ul><!-- /.breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                            autocomplete="off" />
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
                        <div class="col-xs-12">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-6" style="padding:20px; border:1px solid #999999;">
                                <form action="" method="">
                                    <div class="form-group">
                                        <label style="color:green;">Select Business Activity</label>
                                        <select class="form-control" onchange="location = this.value;">
                                            <option value="">Activity Balance Sheet Report</option>
                                            @foreach ($client->professions as $profession)
                                            <option value="{{ route('journal_list_trans',[$client->id,$profession->id])}}">{{$profession->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<!-- Script -->
@stop
