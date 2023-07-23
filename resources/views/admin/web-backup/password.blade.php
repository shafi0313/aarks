@extends('admin.layout.master')
@section('title','Web Backup')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Database / Program File Full Backup</li>
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
                    <div class="col-lg-2 col-md-offset-5 jumbotron text-center" >
                        <div class="card">
                            <div class="card-body">
                                <form action="{{route('backup.setPass')}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="password">Enter Password</label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="pass" class="form-control btn btn-success">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
