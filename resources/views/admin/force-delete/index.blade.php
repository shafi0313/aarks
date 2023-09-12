@extends('admin.layout.master')
@section('title', 'Delete Data Permanently')
@section('content')

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Admin</li>
                    <li>Trash</li>
                    <li class="active">Delete Data Permanently</li>
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
                                <div class="col-md-4 text-center"
                                    style="padding:20px; border:1px solid #999999; margin-top: 50px">
                                    <form autocomplete="off" action="{{ route('admin.forceDelete.destroy') }}"
                                        method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label style="color:red;">Password</label>
                                            <input type="password" name="password" class="form-control" required />
                                        </div>
                                        <button type="submit" class="btn btn-danger btn200"
                                            onclick="return confirm('This action all deleted data permanently. Are you sure?')">Submit</button>
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

@stop
