@extends('admin.layout.master')
@section('title', 'Role')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>

                    <li>
                        <a href="#">Admin</a>
                    </li>
                    <li class="active">User Management</li>
                    <li class="active">Role List</li>
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
                                <div class="clearfix">
                                    <div class="pull-right tableTools-container"></div>
                                </div>
                                <div class="table-header">
                                    Results for All Role
                                    @can('admin.role.create')
                                        <a class="table-header bg-danger"
                                            style="float: right !important; padding-right: 10px;background: #5cb85c"
                                            href="{{ route('role.create') }}">
                                            <i class="fa fa-plus"></i>
                                            Add Role
                                        </a>
                                    @endcan
                                </div>

                                <!-- div.table-responsive -->

                                <!-- div.dataTables_borderWrap -->
                                <div>
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="center">SN</th>
                                                <th>Role</th>
                                                @canany(['admin.role.edit'])
                                                    <th class="center">Action</th>
                                                @endcanany
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($roles as $index => $role)
                                                <tr>
                                                    <td class="center">{{ $index + 1 }}</td>
                                                    <td>{{ $role->name }}</td>
                                                    @canany(['admin.role.edit'])
                                                        <td class="center">
                                                            <div>
                                                                <a title="Role Edit" class="green"
                                                                    href="{{ route('role.edit', $role->id) }}">
                                                                    <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                                </a>
                                                            </div>
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
    <!-- Modal -->
@stop
