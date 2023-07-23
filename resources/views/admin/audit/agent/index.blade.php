@extends('admin.layout.master')
@section('title','Agent Audit')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li class="active">Agent Audit</li>
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
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-6">
                        <select class="form-control" name="agentId" name="agentId" required
                            onchange="location = this.value;">
                            <option disabled selected value> Select Agent</option>
                            @foreach ($agents as $agent)
                            <option value="{{ route('audit.agent_activity', $agent->id) }}">{{$agent->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<!-- inline scripts related to this page -->

@endsection
