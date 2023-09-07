@extends('admin.layout.master')
@section('title', 'Select Profession')
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
                    <li>
                        <a href="{{route('bs_import.index')}}">Import Bank Statement (BST)</a>
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
                <div class="col-md-3">

                </div>

                <div class="col-md-4" style="padding-top:20px;">
                    <h2>Select Professions</h2>
                    <form action="#" name="topform">
                        <div class="form-group">
                            <select class="form-control" id="proid" name="proid" tabindex="7" onchange="location = this.value">
                                <option>Select Profession</option>
                                @foreach($client->professions as $profession)
                                    <option value="{{route('bs_import.BS',['client' => $client->id,'profession' => $profession->id])}}">{{$profession->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
