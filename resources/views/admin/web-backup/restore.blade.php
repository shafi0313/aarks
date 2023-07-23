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
            <div class="row jumbotron">
                <div class="col-lg-6 col-lg-push-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('backup.restore.post')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="password">Select Sql Database *</label>
                                    <input type="file" accept=".sql" name="sql" class="form-control">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-danger" type="submit">Restore</button>
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
