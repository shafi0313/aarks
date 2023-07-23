@extends('admin.layout.master')
@section('title', 'Profession')
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
                    <div class="col-lg-12">
                        <div class="col-md-6 col-lg-offset-3" style="padding:20px; border:1px solid #999999;">
                            <form action="{{ route('payment_sync.list', $client->id) }}" method="get" autocomplete="off">
                                <div class="form-group">
                                    <label style="color:green;">Select Profession</label>
                                    <select class="form-control" name="profession_id">
                                        <option value="">Select Profession</option>
                                        @foreach ($client->professions as $profession)
                                            <option value="{{$profession->id}}">
                                                {{ $profession->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label style="color:green;">Select Customer</label>
                                    <select class="form-control" name="customer_id">
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{$customer->id}}">
                                                {{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary form-control ">Show</button>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->

    <!-- Script -->
@stop
