@extends('admin.layout.master')
@section('title', 'Delete Client Data Permanently')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Client</li>
                    <li>Delete Client Data Permanently</li>
                </ul><!-- /.breadcrumb -->

                <div class="nav-search" id="nav-search">
                    <form class="form-search">
                        <span class="input-icon">
                            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                                autocomplete="off" />
                            <i class="ace-icon fa fa-search nav-search-icon"></i>
                        </span>
                    </form>
                </div>
                <!-- /.nav-search -->
            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-lg-6 col-md-offset-3">
                        <div class="widget-box">
                            <div class="widget-header widget-header-blue widget-header-flat">
                                <h4 class="widget-title lighter">Enter Password</h4>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main">
                                    <form action="{{ route('client.data.checkPassword') }}" method="POST"
                                        class="form-horizontal">
                                        @csrf
                                        <div class="form-group has-warning">
                                            <label for="inputWarning" class="col-xs-12 col-sm-3 control-label">Password:</label>
                                            <div class="col-xs-12 col-sm-8">
                                                <input type="password" name="password" id="inputWarning" required class="width-100">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="wizard-actions">
                                            <button type="submit" class="btn btn-success btn-next">
                                                Submit
                                                <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div><!-- /.widget-main -->
                            </div><!-- /.widget-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
