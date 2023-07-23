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
                    <!-- PAGE CONTENT BEGINS -->                 
                    
                    <p>Visitors Informations</p>
                    <p>
                        <a class="btn btn-primary" href="">Delete All</a>
                    </p>
                    
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-6">
                        <select class="form-control" name="agentId" name="agentId" required onchange="location = this.value;">
                            <option disabled selected value> Select Agent</option>
                            <option value="{{ route('agent_audit_agent_activity') }}">Muhmmad Haroon Arshad</option>
                            <option value="{{ route('agent_audit_agent_activity') }}">Saumya Halpagoda M</option>
                            <option value="{{ route('agent_audit_agent_activity') }}">Jyotirmoy Brahma</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                    </div>
                    
                                    
                {{-- <div class="hr hr32 hr-dotted"></div> --}}
                    
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<!-- inline scripts related to this page -->

@endsection