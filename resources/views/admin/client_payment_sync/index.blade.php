@extends('admin.layout.master')
@section('title', 'Payment Sync')
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
                    <li class="active">General Ledger List</li>
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
                    <div class="col-lg-6 col-lg-offset-3">
                        <div class="card">
                            <div class="card-header">
                                <h2>Search Transaction</h2>
                            </div>
                            <div class="card-body">
                                <form action="{{route('payment_sync.search')}}" method="get" autocomplete="off">
                                    <div class="form-group">
                                        <input type="search" name="search" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-danger form-control" type="submit">Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
