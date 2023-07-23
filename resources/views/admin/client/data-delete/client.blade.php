@extends('admin.layout.master')
@section('title','Delete Client Data Permanently')
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
                    <a href="#">Client</a>
                </li>
                <li class="active">Delete Client Data Permanently</li>
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

            <!-- Settings -->
            {{-- @include('admin.layout.settings') --}}
            <!-- /Settings -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="clearfix">
                                <div class="pull-right tableTools-container"></div>
                            </div>
                            <div class="table-header">
                                All Client
                                @can('admin.client.create')
                                <a class="table-header bg-success"
                                    style="float: right !important; padding-right: 20px; background: #5cb85c"
                                    href="{{ route('client.create') }}">
                                    <i class="fa fa-plus"></i>
                                    Add Client
                                </a>
                                @endcan
                            </div>
                            <div>
                                @include('admin._client_index_table',['from' => 'client_data_delete'])
                            </div>
                        </div>
                    </div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
<!-- Modal -->


{{-- @include('admin.layout.footer') --}}

{{-- @include('admin.layout.delete_model') --}}
@endsection
