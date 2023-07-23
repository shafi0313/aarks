@extends('admin.layout.master')
@section('title', 'Complete Financial Report T Form')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Report</li>
                    <li class="active">Complete Financial Report T Form</li>
                    <li class="active">Select Profession</li>
                </ul><!-- /.breadcrumb -->

                {{-- <div class="nav-search" id="nav-search">
              <form class="form-search">
                <span class="input-icon">
                  <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                  <i class="ace-icon fa fa-search nav-search-icon"></i>
                </span>
              </form>
            </div><!-- /.nav-search --> --}}
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
                                            <label style="color:green;">Select Activity</label>
                                            <select class="form-control" onchange="location = this.value;">
                                                <option value="">Select Activity</option>
                                                @foreach ($client->professions as $profession)
                                                    <option
                                                        value="{{ route('complete_financial_report_tf.financial_report', [$client->id, $profession->id]) }}">
                                                        {{ $profession->name }}
                                                    </option>
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
