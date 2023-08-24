@extends('admin.layout.master')
@section('title', 'Verify & Fixed Transactions')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Tools</li>
                    <li class="active">Verify & Fixed Transactions</li>
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
                                <div class="jumbotron">
                                    <div class="table-header">
                                        All Client
                                    </div>
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="center">SN</th>
                                                <th>Company Name</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th class="hidden-480">E-mail</th>
                                                <th>ABN Number</th>
                                                <th width="63px">Pay Status</th>
                                                <th class="hidden-480">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($clients as $index => $row)
                                                <tr>
                                                    <td class="center">{{ $index + 1 }}</td>
                                                    <td>{{ $row->company }}</td>
                                                    <td>{{ $row->first_name }}</td>
                                                    <td>{{ $row->last_name }}</td>
                                                    <td>{{ $row->email }}</td>
                                                    <td>{{ $row->abn_number }}</td>
                                                    <td class="text-center">
                                                        @isset($row->payment)
                                                            <span class="label label-sm label-success">Active</span>
                                                        @else
                                                            <span class="label label-sm label-danger">Expired</span>
                                                        @endisset
                                                    </td>
                                                    @canany(['admin.verify_account.index'])
                                                        <td>
                                                            <a class="orange"
                                                                href="{{ route('verify_account.profession', $row->id) }}">
                                                                Show Report
                                                            </a>
                                                        </td>
                                                    @endcanany
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
@endsection
