@extends('admin.layout.master')

@section('title', 'General Ledger Report')

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
                        <a href="{{ route('general_ledger.index') }}">General Ledger</a>
                    </li>
                    <li style="color: red; font-weight: bold;">
                      @if(empty($client->company))
                        {{$client->first_name.' '.$client->last_name}}
                      @else
                        {{$client->company}}
                      @endif
                    </li>
                    <li class="active">Select Activity</li>
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
                    <div class="row" >
                        <div class="col-md-3"></div>
                        <div class="col-md-5" style="padding-top:20px;">
                            <h3 class="text-success">Profession</h3>
                            <form action="#" name="topform">
                                <div class="form-group">
                                    <select class="form-control" id="proid" name="proid" tabindex="7" onchange="location = this.value">
                                        <option selected value disabled>Select a Profession</option>
                                        @foreach($client->professions as $profession)
                                            <option value="{{route('general_ledger.date', ['profession' => $profession->id,'client' => $client->id])}}">{{$profession->name}}</option>
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
@stop
